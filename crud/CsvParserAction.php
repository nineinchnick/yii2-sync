<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use netis\utils\crud\UpdateAction;
use nineinchnick\sync\models\parser\BaseCsvParser;
use Yii;

class CsvParserAction extends UpdateAction
{
    /**
     * @var string the scenario to be assigned to a new model before it is validated and updated.
     */
    public $createScenario = BaseCsvParser::SCENARIO_CSV_PARSER;
    /**
     * @var string the scenario to be assigned to the existing model before it is validated and updated.
     */
    public $updateScenario = BaseCsvParser::SCENARIO_CSV_PARSER;
    /**
     * @var string view name used when rendering a HTML response, defaults to current action id
     */
    public $viewName = 'csvParser';

    /**
     * @inheritdoc
     */
    protected function initModel($id)
    {
        $model = parent::initModel($id);
        if (!Yii::$app->getRequest()->getIsPost() && is_null($model->columnsOrder)) {
            $model->columnsOrder = json_encode($model->getDefaultColumns());
        }
        return $model;
    }
}