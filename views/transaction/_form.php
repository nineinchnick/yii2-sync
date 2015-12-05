<?php

/* @var $this \netis\crud\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $fields array */
/* @var $relations array */
/* @var $form yii\widgets\ActiveForm */
/* @var $controller netis\crud\crud\ActiveController */
/* @var $action netis\crud\crud\UpdateAction */
/* @var $view \netis\crud\web\View */
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
