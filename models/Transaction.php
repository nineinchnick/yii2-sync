<?php

namespace nineinchnick\sync\models;

use Yii;
use nineinchnick\sync\models\query\TransactionQuery;

/**
 * This is the model class for table "{{%sync.transactions}}".
 *
 * @property integer $id
 * @property integer $parser_id
 * @property boolean $is_import
 * @property integer $author_id
 * @property integer $editor_id
 * @property string $updated_on
 * @property string $created_on
 *
 * @property File[] $files
 * @property Message[] $messages
 * @property \app\models\User $author
 * @property \app\models\User $editor
 * @property Parser $parser
 */
class Transaction extends \netis\utils\crud\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sync.transactions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parser_id', 'is_import'], 'trim'],
            [['parser_id', 'is_import'], 'default'],
            [['parser_id'], 'required'],
            [['is_import'], 'filter', 'filter' => [Yii::$app->formatter, 'filterBoolean']],
            [['parser_id'], 'integer', 'min' => -0x80000000, 'max' => 0x7FFFFFFF],
            [['is_import'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('nineinchnick/sync/models', 'ID'),
            'parser_id' => Yii::t('nineinchnick/sync/models', 'Parser ID'),
            'is_import' => Yii::t('nineinchnick/sync/models', 'Is Import'),
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
                'attributes' => ['id'],
                'crudLabels' => [
                    'default'  => Yii::t('nineinchnick/sync/models', 'Transaction'),
                    'relation' => Yii::t('nineinchnick/sync/models', 'Transactions'),
                    'index'    => Yii::t('nineinchnick/sync/models', 'Browse Transactions'),
                    'create'   => Yii::t('nineinchnick/sync/models', 'Create Transaction'),
                    'read'     => Yii::t('nineinchnick/sync/models', 'View Transaction'),
                    'update'   => Yii::t('nineinchnick/sync/models', 'Update Transaction'),
                    'delete'   => Yii::t('nineinchnick/sync/models', 'Delete Transaction'),
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
            'files',
            'messages',
            'author',
            'editor',
            'parser',
        ];
    }

    /**
     * @return TransactionQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['transaction_id' => 'id'])->inverseOf('transaction');
    }

    /**
     * @return TransactionQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['transaction_id' => 'id'])->inverseOf('transaction');
    }

    /**
     * @return TransactionQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'author_id']);
    }

    /**
     * @return TransactionQuery
     */
    public function getEditor()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'editor_id']);
    }

    /**
     * @return TransactionQuery
     */
    public function getParser()
    {
        return $this->hasOne(Parser::className(), ['id' => 'parser_id'])->inverseOf('transactions');
    }

    /**
     * @inheritdoc
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }
}