<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 *
 * @var $this \netis\crud\web\View
 * @var $searchModel \yii\base\Model
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $columns array
 * @var $searchFields array
 * @var $controller netis\crud\crud\ActiveController
 */

$attributes['parser_options']['format'] = 'raw';
echo $this->renderDefault('view', [
    'model' => $model,
    'relations' => $relations,
    'attributes' => $attributes,
]);
