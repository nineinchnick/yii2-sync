<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $columns array */
/* @var $buttons array each entry is an array with keys: icon, label, url, options */
/* @var $searchModel \yii\base\Model */
/* @var $searchFields array*/
/* @var $controller netis\utils\crud\ActiveController */

$controller = $this->context;

$columns[0]['buttons']['update'] = function ($url, $model) use ($controller) {
    $url = null;
    foreach ($controller->actionsClassMap as $action => $params) {
        if ($params['modelClass'] === $model->parser_class) {
            $url = [$action, 'id' => $model['id']];
            break;
        }
    }
    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
        'title' => Yii::t('yii', 'Update'),
        'data-pjax' => '0',
    ]);
};
$columns[4]['format'] = 'raw';
echo $this->renderDefault('index', [
    'dataProvider' => $dataProvider,
    'columns' => $columns,
    'buttons' => $buttons,
    'searchModel' => $searchModel,
    'searchFields' => $searchFields,
]);
