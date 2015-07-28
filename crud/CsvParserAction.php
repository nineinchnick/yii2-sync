<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;


use netis\utils\crud\UpdateAction;
use nineinchnick\sync\models\parser\BaseCsvParser;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Yii;

class CsvParserAction extends UpdateAction
{

    public $scenario = BaseCsvParser::SCENARIO_CSV_PARSER;

    /**
     * @inheritdoc
     */
    protected function initModel($id)
    {
        if(empty($this->controller->module->parserList[$this->controller->action->id])){
            throw new InvalidParameterException(Yii::t('nineinchnick/sync/app', 'Parser has to be set.'));
        }
        $parserClass = $this->controller->module->parserList[$this->controller->action->id];
        $this->modelClass = $parserClass;
        $model = parent::initModel($id);
        $model->parser_class = $parserClass;
        $model->scenario = $this->scenario;
        return $model;
    }

}