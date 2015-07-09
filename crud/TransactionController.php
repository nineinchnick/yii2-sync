<?php

/**
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use netis\utils\crud\Action;
use netis\utils\crud\ActiveRecord;
use nineinchnick\sync\models\search\Parser;
use nineinchnick\sync\models\File;

class TransactionController extends \netis\utils\crud\ActiveController
{
    /**
     * @param Action $action
     * @param ActiveRecord $model
     * @param bool $horizontal
     * @param array $privs
     * @param array $defaultActions
     * @param array $confirms
     * @return array
     */
    public function getMenuCurrent(Action $action, $model, $horizontal, $privs, $defaultActions, $confirms)
    {
        $id = $model->isNewRecord ? null : $action->exportKey($model->getPrimaryKey(true));
        $menu = parent::getMenuCurrent($action, $model, $horizontal, $privs, $defaultActions, $confirms);
        if (!$horizontal && $model->isNewRecord) {
            return $menu;
        }
        if ($privs['current']['update'] && ($horizontal || $action->id !== 'process')) {
            $menu['process'] = [
                'label' => \Yii::t('app', 'Process'),
                'icon' => 'cog',
                'url' => ['process', 'id' => $id],
                'active' => false, //$action->id === 'update' && !$model->isNewRecord,
            ];
            if ($model->isNewRecord) {
                $menu['process']['disabled'] = true;
            }
        }
        return $menu;
    }

    public function actionColumnOrder($parserId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $parserId = (int) $parserId;
        if($parserId < 1) {
            return '';
        }
        $modelName = Parser::findOne($parserId)->class;
        $model = new $modelName;
        $files = new File;
        $file = $files->getLastParserFile($parserId);
        $lastColumnOrder = $this->getLastColumnOrder($model, $file->column_order);
        return $this->renderAjax('column_order', ['model' => $model, 'lastColumnOrder' => $lastColumnOrder]);
    }

    public function getLastColumnOrder($model, $lastColumnOrder)
    {
        if (is_null($lastColumnOrder)) {
            return $model;
        }
        parse_str($lastColumnOrder, $columnOrder);
        foreach ($model as $name => $value) {
            if (isset($columnOrder[$name])) {
                continue;
            }
            $columnOrder[$name] = $value;
        }
        return $columnOrder;
    }
}
