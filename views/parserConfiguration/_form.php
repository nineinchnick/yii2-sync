<?php

/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $fields array */
/* @var $relations array */
/* @var $form yii\widgets\ActiveForm */
/* @var $controller netis\utils\crud\ActiveController */
/* @var $action netis\utils\crud\UpdateAction */
/* @var $view \netis\utils\web\View */

use yii\helpers\Html;

$advancedUrl = \yii\helpers\Url::to('advanced-options');
$scripts = <<<JavaScript
    $('#set-advanced-options').click(function() {
        $.ajax({
            url: '$advancedUrl',
            data: {
                model: $('#parserconfiguration-model_class').val(),
                parser: $('#parserconfiguration-parser_class').val(),
            },
            success: function(data) {
                $('div#advanced-options').html(data);
                $('#set-advanced-options').addClass('hidden');
            }
        });
    });

    $('#parserconfiguration-model_class, #parserconfiguration-parser_class').change(function() {
        $('div#advanced-options').html('');
        $('#set-advanced-options').removeClass('hidden');
        $('#parserconfiguration-parser_options').val('');
    });

    $('body').on('click', '#save-advanced-options', function() {
        $('#advanced-options-form').yiiActiveForm('validate');
        console.log($('#advanced-options-form').validated);
        $('#parserconfiguration-parser_options').val($('#advanced-options-form').serialize());
    });
JavaScript;
$this->registerJs($scripts);
$fields[0]['advanced_button'] = Html::button(Yii::t('app', 'Set advanced options'), [
    'class' => 'pull-right btn btn-success',
    'id' => 'set-advanced-options',
]);
$fields[0]['parser_options'] = Html::activeHiddenInput($model, 'parser_options');
?>
<div class="col-md-6" style="margin-top: 32px">
    <?= $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . '_form.php', [
        'model' => $model,
        'fields' => $fields,
        'relations' => $relations,
    ]); ?>
</div>
<div class="col-md-6" id="advanced-options">
</div>