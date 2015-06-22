<?php

namespace nineinchnick\sync\models\search;

use Yii;
use yii\base\Model;
use nineinchnick\sync\models\Message as MessageModel;

/**
 * Message represents the model behind the search form about `\nineinchnick\sync\models\Message`.
 */
class Message extends MessageModel
{
    use \netis\utils\db\ActiveSearchTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'file_id', 'message'], 'trim'],
            [['transaction_id', 'file_id', 'message'], 'default'],
            [['transaction_id', 'file_id'], 'integer', 'min' => -0x80000000, 'max' => 0x7FFFFFFF],
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
