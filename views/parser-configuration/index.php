<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 *
 * @var $this \netis\utils\web\View
 * @var $searchModel \yii\base\Model
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $columns array
 * @var $searchFields array
 * @var $controller netis\utils\crud\ActiveController
 */

use yii\helpers\Html;

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
    'searchModel' => $searchModel,
    'searchFields' => $searchFields,
    'dataProvider' => $dataProvider,
    'columns' => $columns,
]);
