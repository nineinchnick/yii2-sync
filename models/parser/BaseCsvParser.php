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
    public $header = true;
    public $columnsOrder;

    const SCENARIO_CSV_PARSER = 'csvParser';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['length', 'delimiter', 'enclosure', 'escape', 'header', 'columnsOrder'], 'trim', 'on' => self::SCENARIO_CSV_PARSER],
            [['length', 'delimiter', 'enclosure', 'escape', 'header', 'columnsOrder'], 'default', 'on' => self::SCENARIO_CSV_PARSER],
            [['length'], 'integer', 'on' => self::SCENARIO_CSV_PARSER],
            [['delimiter', 'enclosure', 'escape'], 'string', 'max' => 1, 'on' => self::SCENARIO_CSV_PARSER],
            [['header'], 'boolean', 'on' => self::SCENARIO_CSV_PARSER],
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
            'header' => Yii::t('nineinchnick/sync/models', 'Header'),
            'columnsOrder' => Yii::t('nineinchnick/sync/models', 'Columns Order'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        parse_str($this->columnsOrder, $columnOrder);
        $defaultColumns = $this->getDefaultColumns();
        $this->columnsOrder = [];
        foreach ($columnOrder as $column) {
            $this->columnsOrder[$column] = $defaultColumns[$column];
        }
        $this->columnsOrder = json_encode($this->columnsOrder);
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function transfer($file = null)
    {
        if ($file !== null) {
            $file->sent_on = date('Y-m-d H:i:s');
            $file->save(false);
        }
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

    /**
     * Get proper index of attribute column
     *
     * @param array $fields
     * @param array $header
     * @param string $columnsOrder
     * @return array
     */
    protected function prepareAttributes($fields, $header, $columnsOrder)
    {
        $attributes = [];
        $columnsOrder = (array)json_decode($columnsOrder);
        $columnNames = array_keys($columnsOrder);
        if ($header !== null) {
            foreach ($fields as $key => $field) {
                $attributes[trim($header[$key])] = $field;
            }
        } else {
            foreach ($fields as $key => $field) {
                if (isset($columnNames[$key])) {
                    $attributes[$columnNames[$key]] = $field;
                }
            }
        }
        return $attributes;
    }

    /**
     * @param string $content
     * @param BaseCsvParser $parser
     * @return array
     */
    public function readCsv($content, $parser)
    {
        return array_map(function ($line) use ($parser) {
            return str_getcsv($line, $parser->delimiter, $parser->enclosure, $parser->escape);
        }, array_filter(explode("\n", $content)));
    }
}