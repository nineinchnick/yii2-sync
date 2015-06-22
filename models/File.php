<?php

namespace nineinchnick\sync\models;

use Yii;
use nineinchnick\sync\models\query\FileQuery;

/**
 * This is the model class for table "{{%sync.files}}".
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property integer $request_id
 * @property integer $number
 * @property string $url
 * @property string $filename
 * @property integer $size
 * @property string $content
 * @property string $mimetype
 * @property string $hash
 * @property string $sent_on
 * @property string $processed_on
 * @property string $acknowledged_on
 * @property integer $items_count
 * @property integer $processed_count
 * @property integer $author_id
 * @property integer $editor_id
 * @property string $updated_on
 * @property string $created_on
 *
 * @property User $author
 * @property User $editor
 * @property File $request
 * @property File[] $files
 * @property Transaction $transaction
 * @property Message[] $messages
 */
class File extends \netis\utils\crud\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sync.files}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'request_id', 'number', 'url', 'filename', 'size', 'content', 'mimetype', 'hash', 'sent_on', 'processed_on', 'acknowledged_on', 'items_count', 'processed_count'], 'trim'],
            [['transaction_id', 'request_id', 'number', 'url', 'filename', 'size', 'content', 'mimetype', 'hash', 'sent_on', 'processed_on', 'acknowledged_on', 'items_count', 'processed_count'], 'default'],
            [['transaction_id', 'url', 'filename', 'size', 'content'], 'required'],
            [['sent_on', 'processed_on', 'acknowledged_on'], 'filter', 'filter' => [Yii::$app->formatter, 'filterDatetime']],
            [['sent_on', 'processed_on', 'acknowledged_on'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['transaction_id', 'request_id', 'number', 'size', 'items_count', 'processed_count'], 'integer', 'min' => -0x80000000, 'max' => 0x7FFFFFFF],
            [['url', 'filename', 'mimetype', 'hash'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('nineinchnick/sync/models', 'ID'),
            'transaction_id' => Yii::t('nineinchnick/sync/models', 'Transaction ID'),
            'request_id' => Yii::t('nineinchnick/sync/models', 'Request ID'),
            'number' => Yii::t('nineinchnick/sync/models', 'Number'),
            'url' => Yii::t('nineinchnick/sync/models', 'Url'),
            'filename' => Yii::t('nineinchnick/sync/models', 'Filename'),
            'size' => Yii::t('nineinchnick/sync/models', 'Size'),
            'content' => Yii::t('nineinchnick/sync/models', 'Content'),
            'mimetype' => Yii::t('nineinchnick/sync/models', 'Mimetype'),
            'hash' => Yii::t('nineinchnick/sync/models', 'Hash'),
            'sent_on' => Yii::t('nineinchnick/sync/models', 'Sent On'),
            'processed_on' => Yii::t('nineinchnick/sync/models', 'Processed On'),
            'acknowledged_on' => Yii::t('nineinchnick/sync/models', 'Acknowledged On'),
            'items_count' => Yii::t('nineinchnick/sync/models', 'Items Count'),
            'processed_count' => Yii::t('nineinchnick/sync/models', 'Processed Count'),
            'author_id' => Yii::t('nineinchnick/sync/models', 'Author ID'),
            'editor_id' => Yii::t('nineinchnick/sync/models', 'Editor ID'),
            'updated_on' => Yii::t('nineinchnick/sync/models', 'Updated On'),
            'created_on' => Yii::t('nineinchnick/sync/models', 'Created On'),
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'labels' => [
                'class' => 'netis\utils\db\LabelsBehavior',
                'attributes' => ['url'],
                'crudLabels' => [
                    'default'  => Yii::t('nineinchnick/sync/models', 'File'),
                    'relation' => Yii::t('nineinchnick/sync/models', 'Files'),
                    'index'    => Yii::t('nineinchnick/sync/models', 'Browse Files'),
                    'create'   => Yii::t('nineinchnick/sync/models', 'Create File'),
                    'read'     => Yii::t('nineinchnick/sync/models', 'View File'),
                    'update'   => Yii::t('nineinchnick/sync/models', 'Update File'),
                    'delete'   => Yii::t('nineinchnick/sync/models', 'Delete File'),
                ],
            ],
            'blameable' => [
                'class' => 'netis\utils\db\BlameableBehavior',
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'editor_id',
            ],
            'timestamp' => [
                'class' => 'netis\utils\db\TimestampBehavior',
                'updatedAtAttribute' => 'updated_on',
                'createdAtAttribute' => 'created_on',
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function relations()
    {
        return [
            'author',
            'editor',
            'request',
            'files',
            'transaction',
            'messages',
        ];
    }

    /**
     * @return FileQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id'])->inverseOf('files');
    }

    /**
     * @return FileQuery
     */
    public function getEditor()
    {
        return $this->hasOne(User::className(), ['id' => 'editor_id'])->inverseOf('files0');
    }

    /**
     * @return FileQuery
     */
    public function getRequest()
    {
        return $this->hasOne(File::className(), ['id' => 'request_id'])->inverseOf('files');
    }

    /**
     * @return FileQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['request_id' => 'id'])->inverseOf('request');
    }

    /**
     * @return FileQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id'])->inverseOf('files');
    }

    /**
     * @return FileQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['file_id' => 'id'])->inverseOf('file');
    }

    /**
     * @inheritdoc
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }
}
