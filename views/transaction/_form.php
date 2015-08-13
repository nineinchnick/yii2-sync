<?php

/* @var $this \netis\utils\web\View */
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

echo $this->renderDefault('_form', [
    'model' => $model,
    'fields' => $fields,
    'relations' => $relations,
]);
