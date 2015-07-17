<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use \yii;

class ParserConfigurationController extends \netis\utils\crud\ActiveController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return array_merge(parent::actions(), [
            'csvParser' => [
                'class' => 'nineinchnick\sync\crud\CsvParserAction',
            ],
            'xlsParser' => [
                'class' => 'nineinchnick\sync\crud\XlsParserAction',
            ],
        ]);
    }

}