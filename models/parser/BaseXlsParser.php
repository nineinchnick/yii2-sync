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
        ]);
    }

    /**
     * @param string $content
     * @param \nineinchnick\sync\models\ParserConfiguration $parserConfiguration
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function readXls($content, $parserConfiguration)
    {
        $fileName = '/tmp/' . sha1($content) . 'xls';
        touch($fileName);
        file_put_contents($fileName, $content);
        $firstRow = !empty($parserConfiguration->firstRow) ? $parserConfiguration->firstRow : 1;
        $firstCol = !empty($parserConfiguration->firstCol) ? $parserConfiguration->firstCol : 'A';
        $sheet = !empty($parserConfiguration->sheet) ? $parserConfiguration->sheet : 0;

        $inputFileType = \PHPExcel_IOFactory::identify($fileName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($fileName);

        $data = $this->readSheets($objPHPExcel, $sheet, $firstCol, $firstRow);

        unlink($fileName);
        return $data;
    }

    /**
     * @param \PHPExcel $fileObject
     * @param int $sheetNumber
     * @param string $firstCol
     * @param int $firstRow
     * @return array
     */
    protected function readSheets(\PHPExcel $fileObject, $sheetNumber = 0, $firstCol = 'A', $firstRow = 1)
    {
        $sheet = $fileObject->getSheet($sheetNumber);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        return $sheet->rangeToArray($firstCol . $firstRow . ':' . $highestColumn . $highestRow, null, true, true);
    }
}
