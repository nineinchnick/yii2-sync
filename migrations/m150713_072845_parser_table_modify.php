<?php
/**
 * @author Patryk Radziszewski <pradziszewski@netis.pl>
 * @link http://netis.pl/
 * @copyright Copyright (c) 2015 Netis Sp. z o. o.
 */

use yii\db\Schema;
use yii\db\Migration;

class m150713_072845_parser_table_modify extends Migration
{
    protected $schemaName = 'sync';

    public function safeUp()
    {
        $this->execute("ALTER TABLE $this->schemaName.{{%parsers}} ALTER COLUMN parser_options TYPE jsonb USING (parser_options::jsonb)");
        $this->execute("ALTER TABLE $this->schemaName.{{%parsers}} RENAME COLUMN class TO model_class");
        $this->execute("ALTER TABLE $this->schemaName.{{%parsers}} RENAME TO {{%parser_configuration}}");

    }

    public function safeDown()
    {
        $this->execute("ALTER TABLE $this->schemaName.{{%parser_configuration}} RENAME COLUMN model_class TO class");
        $this->execute("ALTER TABLE $this->schemaName.{{%parser_configuration}} ALTER COLUMN parser_options TYPE text");
        $this->execute("ALTER TABLE $this->schemaName.{{%parser_configuration}} RENAME TO {{%parsers}}");
    }
}