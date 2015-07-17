<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 *
 * @var $this yii\web\View
 * @var $searchModel \yii\base\Model
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $columns array
 * @var $searchFields array
 * @var $controller netis\utils\crud\ActiveController
 */

use yii\helpers\Html;

$columns[0]['buttons']['update'] = function ($url, $model) {
    switch ($model->parser_class) {
        case 'app\models\CsvParser':
            $url = ['csvParser', 'id' => $model['id']];
            break;
        case 'app\models\OrderXlsParser':
            $url = ['xlsParser', 'id' => $model['id']];
            break;
    }
    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
        'title' => Yii::t('yii', 'Update'),
        'data-pjax' => '0',
    ]);
};
$columns[5]['format'] = 'raw';
echo $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . 'index.php', [
    'searchModel' => $searchModel,
    'searchFields' => $searchFields,
    'dataProvider' => $dataProvider,
    'columns' => $columns,
]);