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
        $last = [
            'status' => null,
            'order_no' => null,
            'order_id' => null,
        ];
        $nextOrder = true;
        foreach (explode("\r\n", $content) as $key => $line) {
            if (!empty($configuration->header) && $key === 0) {
                continue;
            }
            $fields = explode("\t", $line);
            $attributes = $this->prepareAttributes($fields, $configuration->columnsOrder);
            //couldn't create contractor or product
            if (!$attributes) {
                return false;
            }
            if ($last['order_no'] === $attributes['order_no']) {
                $nextOrder = false;
                //same order number but different status
                if (!is_null($last['status']) && ($last['status'] !== $attributes['order_status'])) {
                    return false;
                }
            }
            if ($nextOrder) {
                //check if this order exists in database
                $model = Order::findOne(['display_number' => $attributes['order_no']]);
                if (is_null($model)) {
                    $model = new Order();
                }
                $model->order_status_id = OrderStatus::STATUS_NEW;//@TODOthis have to be changed to $attributes['order_status'] after we get statuses map;
                $model->contractor_id = $attributes['contractor']->id;
                if (!$model->save()) {

                    return false;
                }
                $model = Order::findOne($model->id);
                $model->display_number = $attributes['order_no'];
                $model->save();
                $last['order_id'] = $model->id;
            }
            //check if OrderItem exists
            $orderItem = OrderItem::findOne(['order_id' => $last['order_id'], 'display_number' => $attributes['item_line']]);
            if (is_null($orderItem)) {
                $orderItem = new OrderItem();
            }
            $orderItem->order_id = $last['order_id'];
            $orderItem->price = $attributes['item_price'];
            $orderItem->currency_code = $attributes['item_currency'];
            $orderItem->quantity = $attributes['item_quantity'];
            $orderItem->product_id = $attributes['product']->id;
            if (!$orderItem->save()) {
                return false;
            }
            $orderItem = OrderItem::findOne($orderItem->id);
            $orderItem->display_number = $attributes['item_line'];
            $orderItem->save();

            $last['status'] = $attributes['order_status'];
            $last['order_no'] = $attributes['order_no'];
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