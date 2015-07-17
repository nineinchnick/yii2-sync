<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

namespace nineinchnick\sync\models\parser;

use Yii;

class BaseCsvParser extends \nineinchnick\sync\models\ParserConfiguration
{
    public $length;
    public $delimiter;
    public $enclosure;
    public $escape;

    const SCENARIO_CSV_PARSER = 'csvParser';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
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
    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_CSV_PARSER => ['name', 'parser_class', 'parser_options', 'length', 'delimiter', 'enclosure', 'escape', 'header'],
        ]);
    }

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
        $content = base64_decode($file->content);
        $configuration = $file->parserConfiguration;
        fgetcsv($content, $configuration->delimeter, $configuration->enclosure, $configuration->escape);

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
}