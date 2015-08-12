<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models\parser;

use Yii;
use yii\base\NotSupportedException;

class BaseCsvParser extends \nineinchnick\sync\models\ParserConfiguration
{
    public $length;
    public $delimiter = ',';
    public $enclosure = '"';
    public $escape = '\\';

    const SCENARIO_CSV_PARSER = 'csvParser';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['length', 'delimiter', 'enclosure', 'escape'], 'trim', 'on' => self::SCENARIO_CSV_PARSER],
            [['length', 'delimiter', 'enclosure', 'escape'], 'default', 'on' => self::SCENARIO_CSV_PARSER],
            [['length'], 'integer', 'on' => self::SCENARIO_CSV_PARSER],
            [['delimiter', 'enclosure', 'escape'], 'string', 'max' => 1, 'on' => self::SCENARIO_CSV_PARSER],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'length' => Yii::t('nineinchnick/sync/models', 'Length'),
            'delimiter' => Yii::t('nineinchnick/sync/models', 'Delimiter'),
            'enclosure' => Yii::t('nineinchnick/sync/models', 'Enclosure'),
            'escape' => Yii::t('nineinchnick/sync/models', 'Escape'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function transfer($file = null)
    {
        $file->sent_on = date('Y-m-d H:i:s');
        $file->save(false);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function process($file)
    {
        throw new NotSupportedException();
    }

    /**
     * @inheritdoc
     */
    public function acknowledge($file)
    {
        $file->acknowledged_on = date('Y-m-d H:i:s');
        $file->save(false);
        return true;
    }
}