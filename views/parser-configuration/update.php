<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 *
 * @var $this yii\web\View
 * @var $model \netis\crud\db\ActiveRecord
 * @var $fields array
 * @var $relations array
 * @var $controller \netis\crud\crud\ActiveController
 */
use yii\helpers\Html;

$controller = $this->context;

$this->title = $model->getCrudLabel($model->isNewRecord ? 'create' : 'update');
if (!$model->isNewRecord) {
    $this->title .= ': ' . $model->__toString();
}
$this->params['breadcrumbs'] = $controller->getBreadcrumbs($controller->action, $model);
$this->params['menu'] = $controller->getMenu($controller->action, $model);
?>

    <h1><span><?= Html::encode($this->title) ?></span></h1>

<?= netis\crud\web\Alerts::widget() ?>

<?php if ($model->scenario === \netis\crud\db\ActiveRecord::SCENARIO_CREATE): ?>
    <ul class="list-group col-md-3">
        <?php foreach ($controller->actionsClassMap as $action => $params): ?>
            <li class="list-group-item">
                <a href="<?= \yii\helpers\Url::to([$action]) ?>">
                    <?= $params['modelClass'] ?>
                    <span class="glyphicon glyphicon-plus pull-right" aria-hidden="true"></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>