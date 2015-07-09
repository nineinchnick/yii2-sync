<?php
/**
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use netis\contractors\models\ContractorLogo;
use netis\erp\models\Contractor;
use nineinchnick\sync\models\File;
use Yii;

/**
 * Combines the \yii\rest\UpdateAction and \yii\rest\CreateAction.
 * @package netis\utils\crud
 */
class TransactionUpdateAction extends \netis\utils\crud\UpdateAction
{

    private $_excelTypes = ['Excel2007', 'Excel5', 'OOCalc', 'Excel2003XML'];

    /**
     * @inheritdoc
     */
    protected function load($model, $request)
    {
        if (($result = parent::load($model, $request))) {
            $model->uploadedFiles = \yii\web\UploadedFile::getInstances($model, 'uploadedFiles');
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function save($model)
    {
        /** @var $model \nineinchnick\sync\models\Transaction */
        if (!$model->save(false) || !$model->saveRelations(Yii::$app->getRequest()->getBodyParams())) {
            return false;
        }
        if ($model->uploadedFiles === null || empty($model->uploadedFiles)) {
            return true;
        }
        $number = $model->getFiles()->count();
        foreach ($model->uploadedFiles as $uploadedFile) {
            if (is_null($content = $this->readXls($uploadedFile))) {
                $content = file_get_contents($uploadedFile->tempName);
            }
            $file = new File();
            $file->setAttributes([
                'number'   => ++$number,
                'url'      => 'file://'.$uploadedFile->name,
                'filename' => $uploadedFile->name,
                'size'     => $uploadedFile->size,
                'content'  => base64_encode($content),
                'mimetype' => $uploadedFile->type,
                'hash'     => sha1($content),
                'sent_on'  => date('Y-m-d H:i:s'),
                'column_order' => $model->columnOrder,
            ], false);
            $file->link('transaction', $model);
            if (!$file->save()) {
                $model->addError('uploadedFiles', Yii::t('nineinchnick/sync/app', 'Failed to save uploaded file.'));

                return false;
            }
        }
        return true;
    }

    public function readXls($uploadedFile)
    {
        $inputFileName = $uploadedFile->tempName;
        $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
        if(!in_array($inputFileName, $this->_excelTypes)) {
            return null;
        }
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        //  Loop through each row of the worksheet in turn
        $data = [];
        for ($row = 1; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row);
            $data[] = join("\t", $rowData[0]);
        }
        return join("\r\n", $data);

    }

}
