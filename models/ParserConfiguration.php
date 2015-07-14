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
 * This is the model class for table "{{%sync.parser_configuration}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $model_class
 * @property string $parser_class
 * @property jsonb $parser_options
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
class ParserConfiguration extends \netis\utils\crud\ActiveRecord
{
    public $length;
    public $delimiter;
    public $enclosure;
    public $escape;
    public $firstCol;
    public $firstRow;
    public $sheet;
    public $header;

    public $csvParserFields = ['length', 'delimiter', 'enclosure', 'escape', 'header'];
    public $xlsParserFields = ['firstCol', 'firstRow', 'sheet', 'header'];

    const SCENARIO_CSV_PARSER = 'csvParser';
    const SCENARIO_XLS_PARSER = 'xlsParser';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sync.parser_configuration}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'model_class', 'parser_class', 'parser_options'], 'trim'],
            [['name', 'model_class', 'parser_class', 'parser_options'], 'default'],
            [['name', 'model_class', 'parser_class'], 'required'],
            [['model_class'], function ($attribute, $params) {
                if (!class_exists($this->$attribute) || !(new $this->$attribute) instanceof \yii\db\ActiveRecord) {
                    $this->addError($attribute, Yii::t('nineinchnick/sync/app', 'Class must be a valid AR model class.'));
                }
            }],
            [['parser_class'], function ($attribute, $params) {
                if (!class_exists($this->$attribute) || !(new $this->$attribute) instanceof self) {
                    $this->addError($attribute, Yii::t('nineinchnick/sync/app', 'Parser class must extend from Parser model.'));
                }
            }],
            [['name', 'model_class', 'parser_class'], 'string', 'max' => 255],
            [['length'], 'integer', 'on' => self::SCENARIO_CSV_PARSER],
            [['delimiter', 'enclosure', 'escape'], 'string', 'max' => 1, 'on' => self::SCENARIO_CSV_PARSER],
            [['header'], 'boolean', 'on' => self::SCENARIO_CSV_PARSER],
            [['firstCol'], 'string', 'on' => self::SCENARIO_XLS_PARSER],
            [['firstRow', 'sheet'], 'integer', 'on' => self::SCENARIO_XLS_PARSER],
            [['header'], 'boolean', 'on' => self::SCENARIO_XLS_PARSER],
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
            'model_class' => Yii::t('nineinchnick/sync/models', 'Model Class'),
            'parser_class' => Yii::t('nineinchnick/sync/models', 'Parser Class'),
            'parser_options' => Yii::t('nineinchnick/sync/models', 'Parser Options'),
            'is_disabled' => Yii::t('nineinchnick/sync/models', 'Is Disabled'),
            'author_id' => Yii::t('nineinchnick/sync/models', 'Author ID'),
            'editor_id' => Yii::t('nineinchnick/sync/models', 'Editor ID'),
            'updated_on' => Yii::t('nineinchnick/sync/models', 'Updated On'),
            'created_on' => Yii::t('nineinchnick/sync/models', 'Created On'),
            'length' => Yii::t('nineinchnick/sync/models', 'Length'),
            'delimiter' => Yii::t('nineinchnick/sync/models', 'Delimiter'),
            'enclosure' => Yii::t('nineinchnick/sync/models', 'Enclosure'),
            'escape' => Yii::t('nineinchnick/sync/models', 'Escape'),
            'fistCol' => Yii::t('nineinchnick/sync/models', 'First Column'),
            'firstRow' => Yii::t('nineinchnick/sync/models', 'First Row'),
            'sheet' => Yii::t('nineinchnick/sync/models', 'Sheet'),
            'header' => Yii::t('nineinchnick/sync/models', 'Header'),
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'labels' => [
                'class' => 'netis\utils\db\LabelsBehavior',
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

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CSV_PARSER => ['name', 'model_class', 'parser_class', 'parser_options', 'length', 'delimiter', 'enclosure', 'escape', 'header'],
            self::SCENARIO_XLS_PARSER => ['name', 'model_class', 'parser_class', 'parser_options', 'firstCol', 'firstRow', 'sheet', 'header'],
        ]);

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
     * @return File|bool bool true when uploading, bool false when downloading and no files are available
     * @throws NotSupportedException
     */
    public function transfer($file = null)
    {
        throw new NotSupportedException();
    }

    /**
     * Parses the files contents.
     * @param $file
     * @throws NotSupportedException
     */
    public function process($file)
    {
        throw new NotSupportedException();
    }

    /**
     * Acknowledges the processing of a downloaded file in a remote service.
     * @param $file
     * @throws NotSupportedException
     */
    public function acknowledge($file)
    {
        throw new NotSupportedException();
    }

    public function beforeSave($insert)
    {
        $parserOptions = [];
        switch ($this->scenario) {
            case self::SCENARIO_CSV_PARSER:
                $fields = $this->csvParserFields;
                break;
            case self::SCENARIO_XLS_PARSER:
                $fields = $this->xlsParserFields;
                break;
            default:
                $fields = [];
        }

        foreach ($fields as $field) {
            $parserOptions[$field] = $this->$field;
        }
        $parserOptions = json_encode($parserOptions);
        $this->parser_options = $parserOptions;
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $parserOptions = json_decode($this->parser_options, true);
        if (is_array($parserOptions)) {
            foreach ($parserOptions as $option => $value) {
                $this->$option = $value;
            }
        }
        return parent::afterFind();
    }
}