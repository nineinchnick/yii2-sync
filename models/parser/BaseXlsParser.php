<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models\parser;


class BaseXlsParser extends BaseCsvParser
{
    public $firstCol;
    public $firstRow;
    public $sheet;
    public $header;
    const SCENARIO_XLS_PARSER = 'xlsParser';


    public function rules()
    {
        return array_merge(parent::rules(), [
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
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_XLS_PARSER => ['name', 'model_class', 'parser_class', 'parser_options', 'firstCol', 'firstRow', 'sheet', 'header'],
        ]);

    }
}