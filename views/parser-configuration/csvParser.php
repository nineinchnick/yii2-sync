<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 *
 * @var \netis\utils\web\View $this
 */

use yii\helpers\Html;

$orderColumnUrl = \yii\helpers\Url::to(['column-order']);
$modelId = (empty($model->id)) ? 0 : $model->id;
$script = <<<JavaScript
$("input[id$='columnsorder']").val($('#sortable-form').serialize());
$( "#sortable" ).sortable({
    update: function(event, ui) {
        $("input[id$='columnsorder']").val($('#sortable-form').serialize());
        changeOrderNumeration();
    }
});
function changeOrderNumeration() {
    $.each($('#sortable .order-numeration'), function(index, item){
        $(this).html(index+1+'.');
    });
}
JavaScript;
$this->registerJs($script);
\yii\jui\JuiAsset::register($this);
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
        <?= $this->renderDefault('_form', [
            'model' => $model,
            'fields' => $fields,
            'relations' => $relations,
        ]); ?>
    </div>
    <div class="col-md-6" id="column-order-div">
        <h4><?= Yii::t('nineinchnick/sync/app', 'Set your column order') ?></h4>

        <form id="sortable-form">
            <ul id="sortable" class="list-group" style="margin-top: 25px">
                <?php $i = 1; foreach (json_decode($model->columnsOrder) as $key => $label): ?>
                    <li class="list-group-item">
                        <span class="order-numeration"><?= $i; ?>.</span>
                        <?= $key . ' - ' . $label ?>
                        <?= Html::input('hidden', 'c' . $i++, $key); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
    </div>
</div>
