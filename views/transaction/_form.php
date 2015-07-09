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
//unset($fields['contractorLogo']);
$script = <<<JavaScript
    //fill columnorder input with last order data
    $("#transaction-columnorder").val($("#column-order-form").serialize());
    //when parser has been chosen render proper columns from model
    if($('#transaction-parser_id').val() !== "" && typeof $('#transaction-parser_id').val() != 'undefined') {
        renderColumnOrderData();
    }
    //when parser is changed render proper columns from model
    $('#transaction-parser_id').change(function() {
        renderColumnOrderData();
    });
    //show/hide column order div
    $('#column-order-button').click(function() {
        $('#column-order-div').toggleClass('hidden');
    });
    //fill columnorder input when checkbox checked
    $('#column-order-div').on('change', '.column-order-checkbox', function() {
        $("#transaction-columnorder").val($("#column-order-form").serialize());
    });

    function renderColumnOrderData() {
        $.ajax({
            url: '{$orderColumnUrl}',
            data: {parserId: $('#transaction-parser_id').val()},
            success: function(data) {
                $('#column-order-div').html(data);
            }
        });
    }
JavaScript;

$this->registerJs($script);

echo \yii\helpers\Html::button(Yii::t('nineinchnick/sync/models', 'Change column order'), ['id' => 'column-order-button']);
?>
    <div class="row">
        <div class="hidden col-md-5" id="column-order-div">
        </div>
    </div>
<?php
$fields['uploadedFiles'] = [
    'formMethod' => 'fileInput',
    'attribute' => 'uploadedFiles[]',
    'arguments' => [['multiple' => true]],
];
$fields['columnOrder'] = \yii\helpers\Html::activeHiddenInput($model, 'columnOrder');

echo $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . '_form.php', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);