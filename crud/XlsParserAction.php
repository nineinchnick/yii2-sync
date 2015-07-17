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
            'entrydt' => Yii::t('nineinchnick/sync/app', 'ENTRYDT'),
            'itembuyer' => Yii::t('nineinchnick/sync/app', 'ITEMBUYER'),
            'contractor_symbol' => Yii::t('nineinchnick/sync/app', 'SUPPNO'),
            'contractor_name' => Yii::t('nineinchnick/sync/app', 'SUPPLIERNAME'),
            'product_symbol' => Yii::t('nineinchnick/sync/app', 'ITEMNO'),
            'product_desc' => Yii::t('nineinchnick/sync/app', 'ITEMDESC'),
            'SupplierItemCode' => Yii::t('nineinchnick/sync/app', 'SupplierItemCode'),
            'SupplierItemDescription' => Yii::t('nineinchnick/sync/app', 'SupplierItemDescription'),
            'order_no' => Yii::t('nineinchnick/sync/app', 'ORDERNO'),
            'item_line' => Yii::t('nineinchnick/sync/app', 'LINE'),
            'SUBLINE' => Yii::t('nineinchnick/sync/app', 'SUBLINE'),
            'order_status' => Yii::t('nineinchnick/sync/app', 'STAT'),
            'item_quantity' => Yii::t('nineinchnick/sync/app', 'QTY'),
            'REQUESTEDDT' => Yii::t('nineinchnick/sync/app', 'REQUESTEDDT'),
            'CONFDT' => Yii::t('nineinchnick/sync/app', 'CONFDT'),
            'DEVDT' => Yii::t('nineinchnick/sync/app', 'DEVDT'),
            'LASTCONFDATE' => Yii::t('nineinchnick/sync/app', 'LASTCONFDATE'),
            'item_currency' => Yii::t('nineinchnick/sync/app', 'CUR'),
            'item_price' => Yii::t('nineinchnick/sync/app', 'UNITPRICE'),
            'DELIVMET' => Yii::t('nineinchnick/sync/app', 'DELIVMET'),
            'INCOTERMS' => Yii::t('nineinchnick/sync/app', 'INCOTERMS'),
            'METKJ' => Yii::t('nineinchnick/sync/app', 'METKJ'),
        ];
    }
}