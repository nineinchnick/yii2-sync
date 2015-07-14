<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models;

use Yii;

/**
 * This is the model class for table "sync.configuration".
 *
 * @property integer $id
 * @property string $name
 * @property string $class
 * @property string $columns_order
 */
class Configuration extends \netis\utils\crud\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sync.configuration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'columns_order', 'class'], 'trim'],
            [['name', 'columns_order', 'class'], 'default'],
            [['name', 'columns_order', 'class'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['class'], 'checkValidClass'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'class' => 'Class',
            'columns_order' => 'Columns Order',
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'labels' => [
                'class' => 'netis\utils\db\LabelsBehavior',
                'attributes' => ['name'],
                'crudLabels' => [
                    'default' => 'Configuration',
                    'relation' => 'Configurations',
                    'index' => 'Browse Configurations',
                    'create' => 'Create Configuration',
                    'read' => 'View Configuration',
                    'update' => 'Update Configuration',
                    'delete' => 'Delete Configuration',
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
        ];
    }

    public function checkValidClass($attribute, $params)
    {
        if (!class_exists($this->$attribute) || !(new $this->$attribute) instanceof \yii\db\ActiveRecord) {
            $this->addError($attribute, Yii::t('nineinchnick/sync/app', 'Class must be a valid AR model class.'));
            return false;
        }
        return true;
    }

    public function beforeSave($insert)
    {
        parse_str($this->columns_order, $columnOrder);

        $i = 1;
        foreach ($columnOrder as &$column) {
            $column = $i++;
        }
        $this->columns_order = json_encode($columnOrder);
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $columns = json_decode($this->columns_order, true);
        asort($columns);
        $this->columns_order = json_encode($columns);
        parent::afterFind();
    }
}