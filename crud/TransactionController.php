<?php

/**
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use netis\crud\crud\Action;
use netis\crud\db\ActiveRecord;

class TransactionController extends \netis\crud\crud\ActiveController
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
        if ($privs['update'] && ($horizontal || $action->id !== 'process')) {
            $menu['process'] = [
                'label' => \Yii::t('nineinchnick/sync/app', 'Process'),
                'icon' => 'cog',
                'url' => ['process', 'id' => $id],
                'active' => false,
            ];
            if ($model->isNewRecord) {
                $menu['process']['disabled'] = true;
            }
        }
        return $menu;
    }
}
