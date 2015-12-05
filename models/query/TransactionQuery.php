<?php

namespace nineinchnick\sync\models\query;

/**
 * This is the ActiveQuery class for [[\nineinchnick\sync\models\Transaction]].
 *
 * @see \nineinchnick\sync\models\Transaction
 */
class TransactionQuery extends \netis\crud\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \nineinchnick\sync\models\Transaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \nineinchnick\sync\models\Transaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}