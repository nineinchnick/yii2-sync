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

    public $firstCol;
    public $firstRow;
    public $sheet;
    public $header;
    public $columnsOrder;

    protected $_optionFields = [];

    /**
     * @inheritdoc
     */
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
     * @inheritdoc
     */
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_XLS_PARSER => ['name', 'parser_class', 'parser_options', 'firstCol', 'firstRow', 'sheet', 'header', 'columnsOrder'],
        ]);

    }

    /**
     * @param $uploadedFile
     * @param $parserConfiguration
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
            $data[] = join("\t", $rowData[0]);
        }
        unlink($fileName);
        return join("\r\n", $data);
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
     * @inheritdoc
     */
    public function afterFind()
    {
        //If view or index action normalize column order to display in line
        if (in_array(Yii::$app->controller->action->id, ['view', 'index'])) {
            $parserOptions = json_decode($this->parser_options, true);
            if (is_array($parserOptions) && isset($parserOptions['columnsOrder'])) {
                $columnsOrder = json_decode($parserOptions['columnsOrder']);
                $order = [];
                $index = 1;
                foreach ($columnsOrder as $column) {
                    $order[$index] = $index . ': ' . $column;
                    $index++;
                }
                $parserOptions['columnsOrder'] = join(', ', $order);
            }
            $this->parser_options = json_encode($parserOptions);
        }
        parent::afterFind();
    }

    /**
     * Get proper index of attribute column
     *
     * @param array $fields
     * @param string $columnsOrder
     * @return array
     */
    public function prepareAttributes($fields, $columnsOrder)
    {
        $attributes = [];
        $index = 0;
        $columnsOrder = json_decode($columnsOrder);
        foreach ($columnsOrder as $key => $label) {
            $attributes[$key] = $fields[$index];
            $index++;
        }
        return $attributes;
    }

}