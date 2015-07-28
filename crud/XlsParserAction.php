<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use nineinchnick\sync\models\parser\BaseXlsParser;
use Yii;

class XlsParserAction extends CsvParserAction
{

    public $scenario = BaseXlsParser::SCENARIO_XLS_PARSER;

    /**
     * @inheritdoc
     */
    protected function initModel($id)
    {
        $model = parent::initModel($id);
        if (!Yii::$app->getRequest()->getIsPost() && is_null($model->columnsOrder)) {
            $model->columnsOrder = json_encode($model->getDefaultColumnsOrder());
        }
        return $model;
    }

}