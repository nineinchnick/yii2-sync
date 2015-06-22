<?php

namespace nineinchnick\sync\models\parser;

use Yii;


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
class Test extends \nineinchnick\sync\models\Parser
{
    /**
     * @inheritdoc
     */
    public function transfer($file = null)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function process($file)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function acknowledge($file)
    {
        return false;
    }
}
