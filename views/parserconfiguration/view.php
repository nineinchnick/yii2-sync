<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

use yii\helpers\Html;
use netis\utils\widgets\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel \yii\base\Model */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $columns array */
/* @var $searchFields array */
/* @var $controller netis\utils\crud\ActiveController */

use yii\helpers\Url;

$attributes['parser_options']['format'] = 'raw';
echo $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . 'view.php', [
    'model' => $model,
    'relations' => $relations,
    'attributes' => $attributes,
]);