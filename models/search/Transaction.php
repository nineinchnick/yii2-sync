<?php

namespace nineinchnick\sync\models\search;

use Yii;
use yii\base\Model;
use nineinchnick\sync\models\Transaction as TransactionModel;

/**
 * Transaction represents the model behind the search form about `\nineinchnick\sync\models\Transaction`.
 */
class Transaction extends TransactionModel
{
    use \netis\utils\db\ActiveSearchTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parser_id', 'is_import', 'author_id', 'editor_id', 'updated_on', 'created_on'], 'trim'],
            [['parser_id', 'is_import', 'author_id', 'editor_id', 'updated_on', 'created_on'], 'default'],
            [['updated_on', 'created_on'], 'filter', 'filter' => [Yii::$app->formatter, 'filterDatetime']],
            [['updated_on', 'created_on'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['is_import'], 'filter', 'filter' => [Yii::$app->formatter, 'filterBoolean']],
            [['parser_id', 'author_id', 'editor_id'], 'integer', 'min' => -0x80000000, 'max' => 0x7FFFFFFF],
            [['is_import'], 'boolean'],
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
