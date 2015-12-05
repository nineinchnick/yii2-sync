<?php

namespace nineinchnick\sync;

class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $controllerNamespace = 'nineinchnick\sync\controllers';

    public $defaultRoute = 'transaction';

    public $controllerMap = [];

    public function getDefaultControllerMap()
    {
        return [
            'parser-configuration' => [
                'class' => 'netis\crud\crud\ActiveController',
                'modelClass' => 'nineinchnick\sync\models\ParserConfiguration',
                'searchModelClass' => 'nineinchnick\sync\models\search\ParserConfiguration',
                'actionsClassMap' => [
                    'csvParser' => [
                        'class' => 'nineinchnick\sync\crud\CsvParserAction',
                        'verbs' => ['GET', 'POST'],
                    ],
                    'xlsParser' => [
                        'class' => 'nineinchnick\sync\crud\XlsParserAction',
                        'verbs' => ['GET', 'POST'],
                    ],
                ],
            ],
            'transaction' => [
                'class' => 'nineinchnick\sync\crud\TransactionController',
                'modelClass' => 'nineinchnick\sync\models\Transaction',
                'searchModelClass' => 'nineinchnick\sync\models\search\Transaction',
                'actionsClassMap' => [
                    'update' => 'nineinchnick\sync\crud\TransactionUpdateAction',
                    'process' => 'nineinchnick\sync\crud\ProcessAction',
                ],
            ],
            'file' => [
                'class' => 'netis\crud\crud\ActiveController',
                'modelClass' => 'nineinchnick\sync\models\File',
                'searchModelClass' => 'nineinchnick\sync\models\search\File',
                'actionsClassMap' => [
                    'download' => 'netis\erp\crud\FileAction',
                ],
            ],
            'message' => [
                'class' => 'netis\crud\crud\ActiveController',
                'modelClass' => 'nineinchnick\sync\models\Message',
                'searchModelClass' => 'nineinchnick\sync\models\search\Message',
            ],
        ];
    }

    public function bootstrap($app)
    {
        $array = array_merge([
            'nineinchnick\sync\models\ParserConfiguration' => '/sync/parser-configuration',
            'nineinchnick\sync\models\Transaction' => '/sync/transaction',
            'nineinchnick\sync\models\File' => '/sync/file',
            'nineinchnick\sync\models\Message' => '/sync/message',
        ], $app->crudModelsMap->data);
        $app->crudModelsMap->data = $array;

        if (!isset($app->i18n->translations['nineinchnick/sync/*'])) {
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
    }

    public function init()
    {
        parent::init();

        $this->controllerMap = array_merge($this->getDefaultControllerMap(), $this->controllerMap);

        $fileFields = function ($action, $context, $model) {
            /** @var $action \netis\crud\crud\Action */
            $fields = $action::getDefaultFields($model);
            foreach ($fields as $key => $field) {
                if ($field !== 'content') {
                    continue;
                }
                switch ($context) {
                    case 'grid':
                        $fields[$key] = [
                            'attribute' => 'content',
                            'format'    => 'raw',
                            'value'     => function ($model, $key, $index, $column) {
                                /** @var $column \yii\grid\DataColumn */
                                return $column->grid->formatter->asCrudLink($model, ['data-pjax' => 0], 'download', \Yii::t('nineinchnick/sync/app', 'Download file'));
                            },
                        ];
                        break;
                    case 'detail':
                        $fields[$key] = function ($model) {
                            return [
                                'attribute' => 'content',
                                'format'    => 'raw',
                                'value'     => \Yii::$app->formatter->asCrudLink($model, [], 'download', \Yii::t('nineinchnick/sync/app', 'Download file')),
                            ];
                        };
                        break;
                    default:
                        unset($fields[$key]);
                        break;
                }
                break;
            }
            return $fields;
        };
        $this->controllerMap['file']['actionsClassMap']['index']['fields'] = $fileFields;
        $this->controllerMap['file']['actionsClassMap']['view']['fields'] = $fileFields;
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('nineinchnick/sync/' . $category, $message, $params, $language);
    }
}
