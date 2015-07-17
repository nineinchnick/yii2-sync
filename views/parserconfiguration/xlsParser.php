<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

use yii\helpers\Html;

$orderColumnUrl = \yii\helpers\Url::to(['column-order']);
$modelId = (empty($model->id)) ? 0 : $model->id;
$script = <<<JavaScript
    $('#basexlsparser-columnsorder').val($('#sortable-form').serialize());
    $( "#sortable" ).sortable({
        update: function(event, ui) {
            $('#basexlsparser-columnsorder').val($('#sortable-form').serialize());
        }
    });

JavaScript;
$this->registerJs($script);
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
$fields[0]['columnsOrder'] = \yii\helpers\Html::activeHiddenInput($model, 'columnsOrder');
?>
<div class="row">
    <div class="col-md-6">
        <?= $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . '_form.php', [
            'model' => $model,
            'fields' => $fields,
            'relations' => $relations,
        ]); ?>
    </div>
    <div class="col-md-6" id="column-order-div">
        <h4><?= Yii::t('app', 'Set your column order') ?></h4>
        <form id="sortable-form">
            <ul id="sortable" class="list-group" style="margin-top: 25px">
                <?php foreach (json_decode($model->columnsOrder) as $key => $label): ?>
                    <li class="list-group-item">
                        <?= $label ?>
                        <?= Html::input('hidden', $key, $label); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
    </div>
</div>