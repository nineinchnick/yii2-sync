<?php

namespace nineinchnick\sync\models\search;

use Yii;
use yii\base\Model;
use nineinchnick\sync\models\Configuration as ConfigurationModel;

/**
 * Configuration represents the model behind the search form about `\nineinchnick\sync\models\Configuration`.
 */
class Configuration extends ConfigurationModel
{
    use \netis\utils\db\ActiveSearchTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'columns_order', 'author_id', 'editor_id', 'updated_on', 'created_on', 'class'], 'trim'],
            [['name', 'columns_order', 'author_id', 'editor_id', 'updated_on', 'created_on', 'class'], 'default'],
            [['updated_on', 'created_on'], 'filter', 'filter' => [Yii::$app->formatter, 'filterDatetime']],
            [['updated_on', 'created_on'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['author_id', 'editor_id'], 'integer', 'min' => -0x80000000, 'max' => 0x7FFFFFFF],
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
