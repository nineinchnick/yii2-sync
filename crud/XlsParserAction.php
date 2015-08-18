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
    /**
     * @var string the scenario to be assigned to a new model before it is validated and updated.
     */
    public $createScenario = BaseXlsParser::SCENARIO_XLS_PARSER;
    /**
     * @var string the scenario to be assigned to the existing model before it is validated and updated.
     */
    public $updateScenario = BaseXlsParser::SCENARIO_XLS_PARSER;
    /**
     * @var string view name used when rendering a HTML response, defaults to current action id
     */
    public $viewName = 'xlsParser';
}