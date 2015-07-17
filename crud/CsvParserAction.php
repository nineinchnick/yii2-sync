<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;


use netis\utils\crud\UpdateAction;
use nineinchnick\sync\models\parser\BaseCsvParser;

class CsvParserAction extends UpdateAction
{

    public $parser = 'app\models\CsvParser';
    public $scenario = BaseCsvParser::SCENARIO_CSV_PARSER;
    public $modelClass = 'nineinchnick\sync\models\parser\BaseCsvParser';

    protected function initModel($id)
    {
        $model = parent::initModel($id);
        $model->parser_class = $this->parser;
        $model->scenario = $this->scenario;
        return $model;
    }

}