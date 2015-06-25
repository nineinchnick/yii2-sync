<?php
/**
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use netis\utils\crud\Action;
use nineinchnick\sync\models\File;
use Yii;
use yii\db\Transaction;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

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
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        /** @var Transaction $trx */
        $trx = $model->getDb()->beginTransaction();
        /** @var File[] $files */
        $files = $model->files;
        $success = true;
        foreach ($files as $file) {
            if ($file->sent_on === null) {
                $success = $file->transfer();
            }
            if ($success && $file->processed_on === null) {
                $success = $model->parser->process($file);
            }
            if ($success && $file->acknowledged_on === null) {
                $success = $model->parser->acknowledge($file);
            }
            if (!$success) {
                break;
            }
        }

        $response = Yii::$app->getResponse();
        $response->setStatusCode(204);

        if ($success) {
            $message = Yii::t('app', 'Record has been successfully processed.');
            $trx->commit();
        } else {
            $message = Yii::t('app', 'Failed to process record.');
            $trx->rollBack();
        }
        $this->setFlash('success', $message);

        $response->getHeaders()->set('Location', Url::toRoute([$this->indexAction], true));
    }
}
