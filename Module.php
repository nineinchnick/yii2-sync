<?php

namespace nineinchnick\sync;

class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $controllerNamespace = 'nineinchnick\sync\controllers';

    public $defaultRoute = '';

    public $controllerMap = [
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
