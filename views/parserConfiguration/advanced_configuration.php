<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 */
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'validateOnSubmit' => true,
    'id' => 'advanced-options-form',
]);

?>
<h2><?= Yii::t('app', 'Advanced options') ?></h2>
<?php
echo $form->field($model, 'length');
echo $form->field($model, 'delimiter');
echo $form->field($model, 'enclosure');
echo $form->field($model, 'escape');
echo \yii\helpers\Html::button(Yii::t('app', 'Save advanced options'), ['class' => 'pull-right btn btn-success', 'id' => 'save-advanced-options']);
ActiveForm::end();