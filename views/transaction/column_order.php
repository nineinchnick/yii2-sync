<?php
/**
 * Created by PhpStorm.
 * User: pradziszewski
 * Date: 09.07.15
 * Time: 11:50
 */
use kartik\sortable\Sortable;

$items = [
    [
        'content' => Yii::t('nineinchnick/sync/models', 'Field') . '<span style="float:right">' . Yii::t('nineinchnick/sync/models', 'Enabled') . '</span>',
        'disabled' => true,
    ]
];
foreach ($lastColumnOrder as $name => $value) {
    $checked = !is_null($lastColumnOrder[$name]) ? 'checked=""' : '';
    $items[] = [
        'content' => $model->getAttributeLabel($name) . '<input type="checkbox" name="' . $name . '" style="float:right" class="column-order-checkbox" ' . $checked,
    ];
}
?>
<form id="column-order-form">
    <?php
    echo Sortable::widget([
        'items' => $items,
        'pluginEvents' => [
            'sortupdate' => 'function() {
                $("#transaction-columnorder").val($("#column-order-form").serialize());
            }',
        ],
    ]);
    ?>
</form>
