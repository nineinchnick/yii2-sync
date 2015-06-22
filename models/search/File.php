<?php

namespace nineinchnick\sync\models\search;

use Yii;
use yii\base\Model;
use nineinchnick\sync\models\File as FileModel;

/**
 * File represents the model behind the search form about `\nineinchnick\sync\models\File`.
 */
class File extends FileModel
{
    use \netis\utils\db\ActiveSearchTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'request_id', 'number', 'url', 'filename', 'size', 'content', 'mimetype', 'hash', 'sent_on', 'processed_on', 'acknowledged_on', 'items_count', 'processed_count', 'author_id', 'editor_id', 'updated_on', 'created_on'], 'trim'],
            [['transaction_id', 'request_id', 'number', 'url', 'filename', 'size', 'content', 'mimetype', 'hash', 'sent_on', 'processed_on', 'acknowledged_on', 'items_count', 'processed_count', 'author_id', 'editor_id', 'updated_on', 'created_on'], 'default'],
            [['sent_on', 'processed_on', 'acknowledged_on', 'updated_on', 'created_on'], 'filter', 'filter' => [Yii::$app->formatter, 'filterDatetime']],
            [['sent_on', 'processed_on', 'acknowledged_on', 'updated_on', 'created_on'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['transaction_id', 'request_id', 'number', 'size', 'items_count', 'processed_count', 'author_id', 'editor_id'], 'integer', 'min' => -0x80000000, 'max' => 0x7FFFFFFF],
            [['url', 'filename', 'mimetype', 'hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
