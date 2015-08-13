<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 *
 * @var \netis\utils\web\View $this
 */

use yii\helpers\Html;

$fields[0]['length'] = [
    'formMethod' => 'textInput',
    'attribute' => 'length',
    'arguments' => [],
];
$fields[0]['delimiter'] = [
    'formMethod' => 'textInput',
    'attribute' => 'delimiter',
    'arguments' => [],
];
$fields[0]['enclosure'] = [
    'formMethod' => 'textInput',
    'attribute' => 'enclosure',
    'arguments' => [],
];
$fields[0]['escape'] = [
    'formMethod' => 'textInput',
    'attribute' => 'escape',
    'arguments' => [],
];
$fields[0]['parser_options'] = Html::activeHiddenInput($model, 'parser_options');
$fields[0]['parser_class'] = Html::activeHiddenInput($model, 'parser_class');
echo $this->renderDefault('_form', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);
