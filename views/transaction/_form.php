<?php

/* @var $this \netis\utils\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $fields array */
/* @var $relations array */
/* @var $form yii\widgets\ActiveForm */
/* @var $controller netis\utils\crud\ActiveController */
/* @var $action netis\utils\crud\UpdateAction */
/* @var $view \netis\utils\web\View */
/* @var $formOptions array form options, will be merged with defaults */
/* @var $buttons array */
/* @var $formBody string if set, allows to override only the form part */

//unset($fields['contractorLogo']);
$fields['uploadedFiles'] = [
    'formMethod' => 'fileInput',
    'attribute' => 'uploadedFiles[]',
    'arguments' => [['multiple' => true]],
];

echo $this->renderDefault('_form', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);
