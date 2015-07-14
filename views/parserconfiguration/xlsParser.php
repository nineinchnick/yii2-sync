<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

use yii\helpers\Html;

$fields[0]['firstCol'] = [
    'formMethod' => 'textInput',
    'attribute' => 'firstCol',
    'arguments' => [],
];
$fields[0]['firstRow'] = [
    'formMethod' => 'textInput',
    'attribute' => 'firstRow',
    'arguments' => [],
];
$fields[0]['sheet'] = [
    'formMethod' => 'textInput',
    'attribute' => 'sheet',
    'arguments' => [],
];
$fields[0]['header'] = [
    'formMethod' => 'checkbox',
    'attribute' => 'header',
    'arguments' => [],
];
$fields[0]['parser_options'] = Html::activeHiddenInput($model, 'parser_options');
$fields[0]['parser_class'] = Html::activeHiddenInput($model, 'parser_class');
echo $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . '_form.php', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);