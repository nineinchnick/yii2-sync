<?php
/**
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use netis\crud\crud\Action;
use nineinchnick\sync\models\File;
use nineinchnick\sync\models\Message;
use Yii;
use yii\base\Exception;
use yii\db\Transaction;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

/**
 * Class ProcessAction
 * @package nineinchnick\sync\crud
 */
class ProcessAction extends Action
{
    /**
     * @var string the name of the index action. This property is need to create the URL
     * when the model is successfully deleted.
     */
    public $indexAction = 'index';

    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @throws ServerErrorHttpException on failure.
     */
    public function run($id)
    {
        /** @var \nineinchnick\sync\models\Transaction $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, 'update', $model);
        }

        /** @var Transaction $trx */
        $trx = $model->getDb()->beginTransaction();
        /** @var File[] $files */
        $files = $model->files;
        $success = true;
        foreach ($files as $file) {
            if ($file->sent_on === null) {
                $success = $model->parserConfiguration->transfer($file);
            }
            if ($success && $file->processed_on === null) {
                try {
                    $success = $model->parserConfiguration->process($file);
                } catch (Yii\Base\Exception $e) {
                    $trx->rollBack();
                    $trx = null;
                    $message = Yii::t('nineinchnick/sync/app', 'Method returned exception type {type} with message {message}', [
                        'type' => get_class($e),
                        'message' => $e->getMessage(),
                    ]);
                    $messageModel = new Message();
                    $messageModel->file_id = $file->id;
                    $messageModel->message = $message;
                    $messageModel->type = Message::TYPE_ERROR;
                    if (!$messageModel->save()) {
                        throw new Exception('Failed to save error message: '.print_r($messageModel->getErrors(), true));
                    }
                    list($key, $flash) = $messageModel->getFlash();
                    $this->setFlash($key, $flash);
                    $success = false;
                }
            }
            if ($success && $file->acknowledged_on === null) {
                $success = $model->parserConfiguration->acknowledge($file);
            }
            if (!$success) {
                break;
            }
        }

        $response = Yii::$app->getResponse();
        $response->setStatusCode(204);

        if ($success) {
            $this->setFlash('success', Yii::t('app', 'Record has been successfully processed.'));
            $trx->commit();
        } else {
            if (!Yii::$app->session->hasFlash('error')) {
                $this->setFlash('error', Yii::t('app', 'Failed to process record.'));
            }
            if ($trx !== null) {
                $trx->rollBack();
            }
        }

        $response->getHeaders()->set('Location', Url::toRoute([$this->indexAction], true));
    }
}
