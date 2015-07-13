<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 */

namespace nineinchnick\sync\crud;


use nineinchnick\sync\models\ParserConfiguration;
use \yii;

class ParserConfigurationController extends \netis\utils\crud\ActiveController
{


    public function actionColumnOrder($modelClass, $modelId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $configurationModel = new Configuration();
        $configurationModel->class = $modelClass;
        $configurationModel->validate(['class']);
        if ($configurationModel->getErrors() !== []) {
            return Yii::t("nineinchnick/sync/models", "Fill class field correctly first");
        }
        $model = new $modelClass;
        $lastConfiguration = Configuration::findOne($modelId);
        $lastColumnOrder = $this->getLastColumnOrder($model, $lastConfiguration);
        return $this->renderAjax('column_order', ['model' => $model, 'lastColumnOrder' => $lastColumnOrder]);
    }

    public function getLastColumnOrder($model, $lastConfiguration)
    {
        if (is_null($lastConfiguration)) {
            return $model;
        }
        $columnOrder = json_decode($lastConfiguration->columns_order, true);
        foreach ($model as $name => $value) {
            if (isset($columnOrder[$name])) {
                continue;
            }
            $columnOrder[$name] = $value;
        }
        return $columnOrder;
    }

}