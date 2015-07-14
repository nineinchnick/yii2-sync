<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;


use netis\utils\crud\UpdateAction;
use nineinchnick\sync\models\ParserConfiguration;

class CsvParserAction extends UpdateAction
{

    public $parser = 'app\models\CsvParser';
    public $scenario = ParserConfiguration::SCENARIO_CSV_PARSER;

    protected function initModel($id)
    {
        $model = parent::initModel($id);
        $model->parser_class = $this->parser;
        $model->scenario = $this->scenario;
        return $model;
    }

}