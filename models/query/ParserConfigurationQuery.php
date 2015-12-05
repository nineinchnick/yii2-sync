<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models\query;

/**
 * This is the ActiveQuery class for [[\nineinchnick\sync\models\ParserConfiguration]].
 *
 * @see \nineinchnick\sync\models\ParserConfiguration
 */
class ParserConfigurationQuery extends \netis\crud\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \nineinchnick\sync\models\ParserConfiguration[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \nineinchnick\sync\models\ParserConfiguration|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}