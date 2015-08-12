<?php

namespace nineinchnick\sync\models;

use Yii;
use nineinchnick\sync\models\query\MessageQuery;

/**
 * This is the model class for table "{{%sync.messages}}".
 *
 * @property integer $id
 * @property integer $transaction_id
 * @property integer $file_id
 * @property string $message
 * @property integer $type
 *
 * @property File $file
 * @property Transaction $transaction
 */
class Message extends \netis\utils\crud\ActiveRecord
{
    const TYPE_INFO = 0;
    const TYPE_NOTICE = 1;
    const TYPE_WARNING = 2;
    const TYPE_ERROR = 3;
    const TYPE_DEBUG = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sync.messages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'file_id', 'message', 'type'], 'trim'],
            [['transaction_id', 'file_id', 'message', 'type'], 'default'],
            [['transaction_id', 'message'], 'required'],
            [['transaction_id', 'file_id', 'type'], 'integer', 'min' => -0x80000000, 'max' => 0x7FFFFFFF]
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
            'file_id' => Yii::t('nineinchnick/sync/models', 'File ID'),
            'message' => Yii::t('nineinchnick/sync/models', 'Message'),
            'type' => Yii::t('nineinchnick/sync/models', 'Type'),
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'labels' => [
                'class' => 'netis\utils\db\LabelsBehavior',
                'attributes' => ['id'],
                'crudLabels' => [
                    'default'  => Yii::t('nineinchnick/sync/models', 'Message'),
                    'relation' => Yii::t('nineinchnick/sync/models', 'Messages'),
                    'index'    => Yii::t('nineinchnick/sync/models', 'Browse Messages'),
                    'create'   => Yii::t('nineinchnick/sync/models', 'Create Message'),
                    'read'     => Yii::t('nineinchnick/sync/models', 'View Message'),
                    'update'   => Yii::t('nineinchnick/sync/models', 'Update Message'),
                    'delete'   => Yii::t('nineinchnick/sync/models', 'Delete Message'),
                ],
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function relations()
    {
        return [
            'file',
            'transaction',
        ];
    }

    /**
     * @return MessageQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id'])->inverseOf('messages');
    }

    /**
     * @return MessageQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id'])->inverseOf('messages');
    }

    /**
     * @inheritdoc
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
    }
}
