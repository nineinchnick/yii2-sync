<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_112256_new_sync_columns extends Migration
{
    protected $schemaName = 'sync';

    public function safeUp()
    {
        $this->addColumn('{{%files}}', 'column_order', 'string');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%files}}', 'column_order');
    }
}
