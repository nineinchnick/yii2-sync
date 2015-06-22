<?php

namespace nineinchnick\sync;

class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $controllerNamespace = 'nineinchnick\sync\controllers';

    public $defaultRoute = 'transaction';

    public $controllerMap = [
        'parser' => [
            'class' => 'netis\utils\crud\ActiveController',
            'modelClass' => 'nineinchnick\sync\models\Parser',
            'searchModelClass' => 'nineinchnick\sync\models\search\Parser',
        ],
        'transaction' => [
            'class' => 'netis\utils\crud\ActiveController',
            'modelClass' => 'nineinchnick\sync\models\Transaction',
            'searchModelClass' => 'nineinchnick\sync\models\search\Transaction',
        ],
        'file' => [
            'class' => 'netis\utils\crud\ActiveController',
            'modelClass' => 'nineinchnick\sync\models\File',
            'searchModelClass' => 'nineinchnick\sync\models\search\File',
        ],
        'message' => [
            'class' => 'netis\utils\crud\ActiveController',
            'modelClass' => 'nineinchnick\sync\models\Message',
            'searchModelClass' => 'nineinchnick\sync\models\search\Message',
        ],
    ];

    public function bootstrap($app)
    {
        $array = array_merge([
            // pass
        ], $app->crudModelsMap->data);
        $app->crudModelsMap->data = $array;

        $app->i18n->translations['nineinchnick/sync/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@nineinchnick/sync/messages',
            'fileMap' => [
                'nineinchnick/sync/app' => 'app.php',
                'nineinchnick/sync/models' => 'models.php',
            ],
        ];
    }

    public function init()
    {
        parent::init();
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('nineinchnick/sync/' . $category, $message, $params, $language);
    }
}
