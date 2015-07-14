<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
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
echo $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . '_form.php', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);