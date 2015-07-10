<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_080403_configuration_table extends Migration
{
    protected $schemaName = 'sync';

    public function safeUp()
    {
        $restrict = 'ON DELETE RESTRICT ON UPDATE CASCADE';

        $this->createTable("{$this->schemaName}.{{%configuration}}", [
            'id' => 'pk',
            'name' => 'string NOT NULL',
            'class' => 'string NOT NULL',
            'columns_order' => 'jsonb NOT NULL',
            'author_id'      => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'editor_id'      => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'updated_on'     => 'timestamp',
            'created_on'     => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable("{$this->schemaName}.{{%configuration}}");
    }
}
