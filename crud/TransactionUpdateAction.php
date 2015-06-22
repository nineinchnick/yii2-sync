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
            $file = new File();
            $content = file_get_contents($uploadedFile->tempName);
            $file->setAttributes([
                'number'   => ++$number,
                'url'      => 'file://'.$uploadedFile->name,
                'filename' => $uploadedFile->name,
                'size'     => $uploadedFile->size,
                'content'  => base64_encode($content),
                'mimetype' => $uploadedFile->type,
                'hash'     => sha1($content),
                'sent_on'  => date('Y-m-d H:i:s'),
            ], false);
            $file->link('transaction', $model);
            if (!$file->save()) {
                $model->addError('uploadedFiles', Yii::t('nineinchnick/sync/app', 'Failed to save uploaded file.'));

                return false;
            }
        }
        return true;
    }
}
