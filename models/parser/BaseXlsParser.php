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
    public $firstCol;
    public $firstRow;
    public $sheet;
    public $header;
    public $columnsOrder;
    const SCENARIO_XLS_PARSER = 'xlsParser';
    protected $_optionFields = [];

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['header', 'firstCol', 'firstRow', 'header', 'columnsOrder'], 'trim', 'on' => self::SCENARIO_XLS_PARSER],
            [['header', 'firstCol', 'firstRow', 'header', 'columnsOrder'], 'default', 'on' => self::SCENARIO_XLS_PARSER],
            [['header', 'firstCol', 'firstRow', 'header', 'columnsOrder'], 'safe'],
            [['header'], 'boolean', 'on' => self::SCENARIO_XLS_PARSER],
            [['firstCol'], 'string', 'on' => self::SCENARIO_XLS_PARSER],
            [['firstRow', 'sheet'], 'integer', 'on' => self::SCENARIO_XLS_PARSER],
            [['header'], 'boolean', 'on' => self::SCENARIO_XLS_PARSER],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'fistCol' => Yii::t('nineinchnick/sync/models', 'First Column'),
            'firstRow' => Yii::t('nineinchnick/sync/models', 'First Row'),
            'sheet' => Yii::t('nineinchnick/sync/models', 'Sheet'),
            'header' => Yii::t('nineinchnick/sync/models', 'Header'),
            'columnsOrder' => Yii::t('nineinchnick/sync/models', 'Columns Order'),
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_XLS_PARSER => ['name', 'model_class', 'parser_class', 'parser_options', 'firstCol', 'firstRow', 'sheet', 'header', 'columnsOrder'],
        ]);

    }

    public function readXls($uploadedFile, $parserConfiguration)
    {
        $firstCol = !empty($parserConfiguration->firstCol) ? $parserConfiguration->firstCol : 'A';
        $firstRow = !empty($parserConfiguration->firstRow) ? $parserConfiguration->firstRow : 1;
        $sheet = !empty($parserConfiguration->sheet) ? $parserConfiguration->sheet : 0;
        $inputFileName = $uploadedFile;
        $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
        var_dump($inputFileType);
        die();
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet($sheet);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        //  Loop through each row of the worksheet
        $data = [];
        for ($row = $firstRow; $row <= $highestRow; $row++) {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray($firstCol . $row . ':' . $highestColumn . $row);
            $data[] = join("\t", $rowData[0]);
        }
        return join("\r\n", $data);
    }

    public function beforeSave($insert)
    {
        parse_str($this->columnsOrder, $columnOrder);
        $this->columnsOrder = json_encode($columnOrder);
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        $columns = json_decode($this->columnsOrder, true);
        if(!is_null($columns)) {
            asort($columns);
            $this->columnsOrder = json_encode($columns);
        }
    }

}