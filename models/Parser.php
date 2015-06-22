<?php

namespace nineinchnick\sync\models;

use Yii;
use nineinchnick\sync\models\query\ParserQuery;

/**
 * This is the model class for table "{{%sync.parsers}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $class
 * @property string $parser_class
 * @property string $parser_options
 * @property boolean $is_disabled
 * @property integer $author_id
 * @property integer $editor_id
 * @property string $updated_on
 * @property string $created_on
 *
 * @property \app\models\User $author
 * @property \app\models\User $editor
 * @property Transaction[] $transactions
 */
class Parser extends \netis\utils\crud\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sync.parsers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class', 'parser_class', 'parser_options'], 'trim'],
            [['name', 'class', 'parser_class', 'parser_options'], 'default'],
            [['name', 'class', 'parser_class'], 'required'],
            [['name', 'class', 'parser_class'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('nineinchnick/sync/models', 'ID'),
            'name' => Yii::t('nineinchnick/sync/models', 'Name'),
            'class' => Yii::t('nineinchnick/sync/models', 'Class'),
            'parser_class' => Yii::t('nineinchnick/sync/models', 'Parser Class'),
            'parser_options' => Yii::t('nineinchnick/sync/models', 'Parser Options'),
            'is_disabled' => Yii::t('nineinchnick/sync/models', 'Is Disabled'),
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
                'attributes' => ['name'],
                'crudLabels' => [
                    'default'  => Yii::t('nineinchnick/sync/models', 'Parser'),
                    'relation' => Yii::t('nineinchnick/sync/models', 'Parsers'),
                    'index'    => Yii::t('nineinchnick/sync/models', 'Browse Parsers'),
                    'create'   => Yii::t('nineinchnick/sync/models', 'Create Parser'),
                    'read'     => Yii::t('nineinchnick/sync/models', 'View Parser'),
                    'update'   => Yii::t('nineinchnick/sync/models', 'Update Parser'),
                    'delete'   => Yii::t('nineinchnick/sync/models', 'Delete Parser'),
                ],
            ],
            'toggable' => [
                'class' => 'netis\utils\db\ToggableBehavior',
                'disabledAttribute' => 'is_disabled',
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
            'transactions',
        ];
    }

    /**
     * @return ParserQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'author_id']);
    }

    /**
     * @return ParserQuery
     */
    public function getEditor()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'editor_id']);
    }

    /**
     * @return ParserQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['parser_id' => 'id'])->inverseOf('parser');
    }

    /**
     * @inheritdoc
     * @return ParserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParserQuery(get_called_class());
    }
}
