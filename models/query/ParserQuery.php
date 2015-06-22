<?php

namespace nineinchnick\sync\models\query;

/**
 * This is the ActiveQuery class for [[\nineinchnick\sync\models\Parser]].
 *
 * @see \nineinchnick\sync\models\Parser
 */
class ParserQuery extends \netis\utils\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \nineinchnick\sync\models\Parser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \nineinchnick\sync\models\Parser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}