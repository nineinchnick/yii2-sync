<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii\db\ActiveRecord */
/* @var $fields array */
/* @var $relations array */
/* @var $controller netis\utils\crud\ActiveController */

$controller = $this->context;

$this->title = $model->getCrudLabel($model->isNewRecord ? 'create' : 'update');
if (!$model->isNewRecord) {
    $this->title .= ': ' . $model->__toString();
}
$this->params['breadcrumbs'] = $controller->getBreadcrumbs($controller->action, $model);
$this->params['menu'] = $controller->getMenu($controller->action, $model);
?>

    <h1><span><?= Html::encode($this->title) ?></span></h1>

<?= netis\utils\web\Alerts::widget() ?>

<?php if ($model->scenario === 'create'): ?>
    <ul class="list-group col-md-3">
        <?php foreach ($controller->module->parserList as $action => $namespace): ?>
            <li class="list-group-item">
                <a href="<?= \yii\helpers\Url::to([$action]) ?>">
                    <?= $namespace ?> <span class="glyphicon glyphicon-plus pull-right" aria-hidden="true"></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>