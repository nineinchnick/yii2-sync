<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use nineinchnick\sync\models\parser\BaseXlsParser;
use Yii;

class XlsParserAction extends CsvParserAction
{

    public $parser = 'app\models\OrderXlsParser';
    public $scenario = BaseXlsParser::SCENARIO_XLS_PARSER;
    public $modelClass = 'nineinchnick\sync\models\parser\BaseXlsParser';

    /**
     * @inheritdoc
     */
    protected function initModel($id)
    {
        $model = parent::initModel($id);
        if (!Yii::$app->getRequest()->getIsPost() && is_null($model->columnsOrder)) {
            $model->columnsOrder = json_encode($this->getDefaultColumnsOrder());
        }
        return $model;
    }

    /**
     * Static map of columns order
     * @return array
     */
    public function getDefaultColumnsOrder()
    {
        return [
            'entrydt' => Yii::t('nineinchnick/sync/models', 'Entry Date'),
            'itembuyer' => Yii::t('nineinchnick/sync/models', 'Item Buyer'),
            'contractor_symbol' => Yii::t('nineinchnick/sync/models', 'Supplier Number'),
            'contractor_name' => Yii::t('nineinchnick/sync/models', 'Supplier Name'),
            'product_symbol' => Yii::t('nineinchnick/sync/models', 'Item Number'),
            'product_desc' => Yii::t('nineinchnick/sync/models', 'Item Description'),
            'SupplierItemCode' => Yii::t('nineinchnick/sync/models', 'Supplier Item Code'),
            'SupplierItemDescription' => Yii::t('nineinchnick/sync/models', 'Supplier Item Description'),
            'order_no' => Yii::t('nineinchnick/sync/models', 'Order Number'),
            'item_line' => Yii::t('nineinchnick/sync/models', 'Line'),
            'SUBLINE' => Yii::t('nineinchnick/sync/models', 'Subline'),
            'order_status' => Yii::t('nineinchnick/sync/models', 'Status'),
            'item_quantity' => Yii::t('nineinchnick/sync/models', 'Quantity'),
            'REQUESTEDDT' => Yii::t('nineinchnick/sync/models', 'Request Date'),
            'CONFDT' => Yii::t('nineinchnick/sync/models', 'Confirm Date'),
            'DEVDT' => Yii::t('nineinchnick/sync/models', 'Dev Date'),
            'LASTCONFDATE' => Yii::t('nineinchnick/sync/models', 'Last Confirm Date'),
            'item_currency' => Yii::t('nineinchnick/sync/models', 'Currency'),
            'item_price' => Yii::t('nineinchnick/sync/models', 'Unit Price'),
            'DELIVMET' => Yii::t('nineinchnick/sync/models', 'Delivment'),
            'INCOTERMS' => Yii::t('nineinchnick/sync/models', 'Incoterms'),
            'METKJ' => Yii::t('nineinchnick/sync/models', 'Metkj'),
        ];
    }
}