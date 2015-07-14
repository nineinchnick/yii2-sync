<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\crud;

use nineinchnick\sync\models\ParserConfiguration;

class XlsParserAction extends CsvParserAction
{

    public $parser = 'app\models\XlsParser';
    public $scenario = ParserConfiguration::SCENARIO_XLS_PARSER;

}