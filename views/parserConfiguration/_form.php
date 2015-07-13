<?php

/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $fields array */
/* @var $relations array */
/* @var $form yii\widgets\ActiveForm */
/* @var $controller netis\utils\crud\ActiveController */
/* @var $action netis\utils\crud\UpdateAction */
/* @var $view \netis\utils\web\View */

$orderColumnUrl = \yii\helpers\Url::to(['column-order']);
$modelId = (empty($model->id)) ? 0 : $model->id;
//unset($fields['contractorLogo']);
$script = <<<JavaScript
    renderColumnOrderData($('#configuration-class').val(), '$modelId');
    $('#configuration-class').focusout(function() {
        renderColumnOrderData($(this).val(), 0);
    });
    $('#column-order-button').click(function() {
        $('#column-order-div').toggleClass('hidden');
    });
    $('#column-order-div').on('change', '.column-order-checkbox', function() {
        $("#configuration-columns_order").val($("#column-order-form").serialize());
    });

    function renderColumnOrderData(modelClass, modelId) {
        $.ajax({
            url: '{$orderColumnUrl}',
            data: {
                modelClass: modelClass,
                modelId: modelId
            },
            success: function(data) {
                $('#column-order-div').html(data);
                $("#configuration-columns_order").val($("#column-order-form").serialize());
            }
        });
    }
JavaScript;

$this->registerJs($script);
$fields[0]['columns_order'] = \yii\helpers\Html::activeHiddenInput($model, 'columns_order');
$fields[0]['class'] = [
    'formMethod' => 'textInput',
    'attribute' => 'class',
    'arguments' => [],
];
$fields[0]['column-order-button'] = \yii\helpers\Html::button(Yii::t('nineinchnick/sync/models', 'Change column order'), ['id' => 'column-order-button']);
?>
<div class="row">
    <div class="hidden col-md-5" id="column-order-div"></div>
</div>
<?php
echo $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . '_form.php', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);
?>
