<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 * @TODO trace error messages
 */

namespace nineinchnick\sync\models\parser;

use netis\assortment\models\search\Product;
use netis\contractors\models\Contractor;
use netis\orders\models\OrderStatus;
use nineinchnick\sync\models\parser\BaseXlsParser;
use yii\base\InvalidCallException;
use netis\orders\models\Order;
use netis\orders\models\OrderItem;
use yii\base\NotSupportedException;

class ExampleXlsParser extends BaseXlsParser
{
    /**
     * @inheritdoc
     */
    public function transfer($file = null)
    {
        throw new NotSupportedException();
    }

    /**
     * Parses the files contents.
     * @param $file
     * @return bool
     */
    public function process($file)
    {
        $configuration = $file->parserConfiguration;
        $content = $this->readXls(base64_decode($file->content), $configuration);
        $header = null;
        foreach ($content as $key => $fields) {
            if (!empty($configuration->header) && $key === 0) {
                $header = $fields;
                continue;
            }
            $attributes = $this->prepareAttributes($fields, $header, $configuration->columnsOrder);
            //process
        }
        return true;

    }

    /**
     * @inheritdoc
     */
    public function acknowledge($file)
    {
        throw new NotSupportedException();
    }
}