<?php
/**
 * Created by PhpStorm.
 * User: pradziszewski
 * Date: 13.07.15
 * Time: 14:18
 */

namespace nineinchnick\sync\crud;


use netis\utils\crud\UpdateAction;
use nineinchnick\sync\models\ParserConfiguration;

class CsvParserAction extends UpdateAction
{

    public $csvParser = 'app\models\CsvParser';

    protected function initModel($id)
    {
        $model = parent::initModel($id);
        $model->parser_class = $this->csvParser;
        $model->scenario = ParserConfiguration::SCENARIO_CSV_PARSER;
        return $model;
    }

}