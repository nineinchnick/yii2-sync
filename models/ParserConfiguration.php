<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models;

use Yii;
use nineinchnick\sync\models\query\ParserConfigurationQuery;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "{{%sync.parser_configurations}}".
 *
 * @property integer $id
 * @property string $name
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
class ParserConfiguration extends \netis\crud\db\ActiveRecord
{
    protected $_mainAttributes = ['name', 'parser_class', 'parser_options'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sync.parser_configurations}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parser_class', 'parser_options'], 'trim'],
            [['name', 'parser_class', 'parser_options'], 'default'],
            [['name', 'parser_class'], 'required'],
            [['parser_class'], function ($attribute, $params) {
                if (!class_exists($this->$attribute) || !(new $this->$attribute) instanceof self) {
                    $this->addError($attribute, Yii::t('nineinchnick/sync/app', 'Parser class must extend from Parser model.'));
                }
            }],
            [['name', 'parser_class'], 'string', 'max' => 255],
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
            'parser_class' => Yii::t('nineinchnick/sync/models', 'Parser Class'),
            'parser_options' => Yii::t('nineinchnick/sync/models', 'Parser Options'),
            'is_disabled' => Yii::t('nineinchnick/sync/models', 'Is Disabled'),
            'author_id' => Yii::t('nineinchnick/sync/models', 'Author ID'),
            'editor_id' => Yii::t('nineinchnick/sync/models', 'Editor ID'),
            'updated_on' => Yii::t('nineinchnick/sync/models', 'Updated On'),
            'created_on' => Yii::t('nineinchnick/sync/models', 'Created On'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'labels' => [
                'class' => 'netis\crud\db\LabelsBehavior',
                'attributes' => ['name'],
                'crudLabels' => [
                    'default' => Yii::t('nineinchnick/sync/models', 'Parser Configuration'),
                    'relation' => Yii::t('nineinchnick/sync/models', 'Parser Configurations'),
                    'index' => Yii::t('nineinchnick/sync/models', 'Browse Parser Configurations'),
                    'create' => Yii::t('nineinchnick/sync/models', 'Create Parser Configuration'),
                    'read' => Yii::t('nineinchnick/sync/models', 'View Parser Configuration'),
                    'update' => Yii::t('nineinchnick/sync/models', 'Update Parser Configuration'),
                    'delete' => Yii::t('nineinchnick/sync/models', 'Delete Parser Configuration'),
                ],
            ],
            'toggable' => [
                'class' => 'netis\crud\db\ToggableBehavior',
                'disabledAttribute' => 'is_disabled',
            ],
            'blameable' => [
                'class' => 'netis\crud\db\BlameableBehavior',
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'editor_id',
            ],
            'timestamp' => [
                'class' => 'netis\crud\db\TimestampBehavior',
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
     * @return ParserConfigurationQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'author_id']);
    }

    /**
     * @return ParserConfigurationQuery
     */
    public function getEditor()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'editor_id']);
    }

    /**
     * @return ParserConfigurationQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['parser_id' => 'id'])->inverseOf('parserConfiguration');
    }

    /**
     * @inheritdoc
     * @return ParserConfigurationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParserConfigurationQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return new $row['parser_class'];
    }

    /**
     * Either uploads a prepared file or downloads next file from a remote service.
     * Returns bool false if no files are available.
     * @param File $file
     * @return File|bool file model or bool false when downloading and no files are available
     * @throws NotSupportedException
     */
    public function transfer($file = null)
    {
        throw new NotSupportedException();
    }

    /**
     * Parses the files contents.
     * @param File $file
     * @throws NotSupportedException
     */
    public function process($file)
    {
        throw new NotSupportedException();
    }

    /**
     * Acknowledges the processing of a downloaded file in a remote service.
     * @param File $file
     * @throws NotSupportedException
     */
    public function acknowledge($file)
    {
        throw new NotSupportedException();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $parserOptions = [];
        foreach ($this->activeAttributes() as $attr) {
            if (!in_array($attr, $this->_mainAttributes)) {
                $parserOptions[$attr] = $this->$attr;
            }
        }
        $this->parser_options = json_encode($parserOptions);
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $parserOptions = json_decode($this->parser_options, true);
        if (!is_array($parserOptions)) {
            return parent::afterFind();
        }
        $attributeLabels = $this->attributeLabels();
        $newOptions = [];
        foreach ($parserOptions as $option => $value) {
            //fill inputs;
            $this->$option = $value;
            //format parserOptions
            $newOptions[$attributeLabels[$option]] = $attributeLabels[$option] . ': ' . $value;

        }
        $this->parser_options = implode('<br>', $newOptions);
        return parent::afterFind();
    }
}
