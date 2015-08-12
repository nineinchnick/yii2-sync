<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models\parser;

use Yii;

class BaseXlsParser extends BaseCsvParser
{
    const SCENARIO_XLS_PARSER = 'xlsParser';

    public $firstCol = 'A';
    public $firstRow = 1;
    public $sheet = 0;
    public $header = true;
    public $columnsOrder;

    protected $_optionFields = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['firstCol', 'firstRow', 'sheet', 'header', 'columnsOrder'], 'trim', 'on' => self::SCENARIO_XLS_PARSER],
            [['firstCol', 'firstRow', 'sheet', 'header', 'columnsOrder'], 'default', 'on' => self::SCENARIO_XLS_PARSER],
            [['firstCol'], 'string', 'on' => self::SCENARIO_XLS_PARSER],
            [['firstRow', 'sheet'], 'integer', 'on' => self::SCENARIO_XLS_PARSER],
            [['header'], 'boolean', 'on' => self::SCENARIO_XLS_PARSER],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'firstCol' => Yii::t('nineinchnick/sync/models', 'First Column'),
            'firstRow' => Yii::t('nineinchnick/sync/models', 'First Row'),
            'sheet' => Yii::t('nineinchnick/sync/models', 'Sheet'),
            'header' => Yii::t('nineinchnick/sync/models', 'Header'),
            'columnsOrder' => Yii::t('nineinchnick/sync/models', 'Columns Order'),
        ]);
    }

    /**
     * @param $uploadedFile
     * @param \nineinchnick\sync\models\ParserConfiguration $parserConfiguration
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function readXls($uploadedFile, $parserConfiguration)
    {
        $fileName = '/tmp/' . sha1($uploadedFile) . 'xls';
        touch($fileName);
        file_put_contents($fileName, $uploadedFile);
        $firstRow = !empty($parserConfiguration->firstRow) ? $parserConfiguration->firstRow : 1;
        $firstCol = !empty($parserConfiguration->firstCol) ? $parserConfiguration->firstCol : 'A';
        $sheet = !empty($parserConfiguration->sheet) ? $parserConfiguration->sheet : 0;
        $inputFileType = \PHPExcel_IOFactory::identify($fileName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($fileName);
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet($sheet);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        //  Loop through each row of the worksheet
        $data = [];
        for ($row = $firstRow; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray($firstCol . $row . ':' . $highestColumn . $row);
            $data[] = $rowData[0];
        }
        unlink($fileName);
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        parse_str($this->columnsOrder, $columnOrder);
        $this->columnsOrder = json_encode($columnOrder);
        return parent::beforeSave($insert);
    }

    /**
     * Get proper index of attribute column
     *
     * @param array $fields
     * @param array $header
     * @param string $columnsOrder
     * @return array
     */
    protected function prepareAttributes($fields, $header, $columnsOrder)
    {
        $attributes = [];
        $columnsOrder = (array)json_decode($columnsOrder);
        $columnNames = array_keys($columnsOrder);
        if ($header !== null) {
            foreach ($header as $key) {
                $index = array_search($key, $columnNames);
                $attributes[$key] = $fields[$index];
            }
        } else {
            foreach ($columnNames as $index => $key) {
                $attributes[$key] = $fields[$index];
            }
        }
        return $attributes;
    }
}
