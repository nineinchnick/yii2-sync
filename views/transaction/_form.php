<?php

/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $fields array */
/* @var $relations array */
/* @var $form yii\widgets\ActiveForm */
/* @var $controller netis\utils\crud\ActiveController */
/* @var $action netis\utils\crud\UpdateAction */
/* @var $view \netis\utils\web\View */

//unset($fields['contractorLogo']);
$fields['uploadedFiles'] = [
    'formMethod' => 'fileInput',
    'attribute' => 'uploadedFiles[]',
    'arguments' => [['multiple' => true]],
];

echo $this->renderFile($this->getDefaultViewPath() . DIRECTORY_SEPARATOR . '_form.php', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);
