<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 */

namespace nineinchnick\sync\crud;


use nineinchnick\sync\models\ParserConfiguration;
use Symfony\Component\Yaml\Parser;
use \yii;
use netis\utils\widgets\FormBuilder;

class ParserConfigurationController extends \netis\utils\crud\ActiveController
{


    public function actions()
    {
        return array_merge(parent::actions(),
            ['csvParser' => [
                'class' => 'nineinchnick\sync\crud\CsvParserAction',
            ],
            ])
        ;
    }

    public function actionAdvancedOptions($model, $parser)
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $modelParserConfiguration = new ParserConfiguration();
        $modelParserConfiguration->model_class = $model;
        $modelParserConfiguration->parser_class = $parser;
        if (!$modelParserConfiguration->validate(['model_class', 'parser_class'])) {
//            return Yii::t("nineinchnick/sync/models", "Fill Model and Parser classes correctly first");
        }
        return $this->renderAjax('advanced_configuration', ['model' => $modelParserConfiguration]);
    }

//    public function actionCsvParser()
//    {
//        $model = new ParserConfiguration();
//        $model->parser_class = $this->csvParser;
//        $fields = FormBuilder::getFormFields($model, $this->getFields($model, 'form'), false);
//
//        return $this->render('csv_parser', [
//            'model' => $model,
//            'fields' => $fields,
//        ]);
//    }

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