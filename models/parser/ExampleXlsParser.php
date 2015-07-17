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

class ExampleXlsParser extends BaseXlsParser
{
    /**
     * Either uploads a prepared file or downloads next file from a remote service.
     * Returns bool false if no files are available.
     * @param File $file
     * @return File|bool bool true when uploading, bool false when downloading and no files are available
     */
    public function transfer($file = null)
    {
        $file->sent_on = date('Y-m-d H:i:s');
        $file->save(false);
        return true;
    }

    /**
     * Parses the files contents.
     * @param $file
     * @return bool
     * @throws Exception
     */
    public function process($file)
    {
        $configuration = $file->parserConfiguration;
        $content = $this->readXls(base64_decode($file->content), $configuration);
        foreach (explode("\r\n", $content) as $key => $line) {
            if (!empty($configuration->header) && $key === 0) {
                continue;
            }
            $fields = explode("\t", $line);
            $attributes = $this->prepareAttributes($fields, $configuration->columnsOrder);
            //process
        }
        return true;

    }

    /**
     * Acknowledges the processing of a downloaded file in a remote service.
     * @param $file
     * @return bool
     */
    public function acknowledge($file)
    {
        $file->acknowledged_on = date('Y-m-d H:i:s');
        $file->save(false);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function prepareAttributes($fields, $columnsOrder)
    {
        $attributes = parent::prepareAttributes($fields, $columnsOrder);
        $attributes['contractor'] = $this->getContractor($attributes);
        if (!$attributes['contractor']) {
            return false;
        }
        $attributes['product'] = $this->getProduct($attributes);
        if (!$attributes['product']) {
            return false;
        }
        return $attributes;
    }

    /**
     * @param array $attributes
     * @return bool|Contractor|null|static
     */
    public function getContractor($attributes)
    {
        $contractor = Contractor::findOne(['symbol' => $attributes['contractor_symbol']]);
        if (!is_null($contractor)) {
            return $contractor;
        }
        $contractorModel = new Contractor();
        $contractorModel->symbol = $attributes['contractor_symbol'];
        $contractorModel->name = $attributes['contractor_name'];
        if ($contractorModel->save()) {
            return $contractorModel;
        } else {
            return false;
        }
    }

    /**
     * @param array $attributes
     * @return bool|Product|null|static
     */
    public function getProduct($attributes)
    {
        $product = Product::findOne(['symbol' => $attributes['product_symbol']]);
        if (!is_null($product)) {
            return $product;
        }
        $productModel = new Product();
        $productModel->symbol = $attributes['product_symbol'];
        $productModel->name = $attributes['product_desc'];
        $productModel->is_disabled = 'f';
        if ($productModel->save()) {
            return $productModel;
        } else {
            return false;
        }
    }
}