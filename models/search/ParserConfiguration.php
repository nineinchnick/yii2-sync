<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models\search;

use Yii;
use yii\base\Model;
use nineinchnick\sync\models\ParserConfiguration as ParserConfigurationModel;

/**
 * ParserConfiguration represents the model behind the search form about `\nineinchnick\sync\models\ParserConfiguration`.
 */
class ParserConfiguration extends ParserConfigurationModel
{
    use \netis\utils\db\ActiveSearchTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parser_class', 'parser_options', 'is_disabled', 'author_id', 'editor_id', 'updated_on', 'created_on'], 'trim'],
            [['name', 'parser_class', 'parser_options', 'is_disabled', 'author_id', 'editor_id', 'updated_on', 'created_on'], 'default'],
            [['updated_on', 'created_on'], 'filter', 'filter' => [Yii::$app->formatter, 'filterDatetime']],
            [['updated_on', 'created_on'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['is_disabled'], 'filter', 'filter' => [Yii::$app->formatter, 'filterBoolean']],
            [['name', 'parser_class'], 'string', 'max' => 255],
            [['is_disabled'], 'boolean'],
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