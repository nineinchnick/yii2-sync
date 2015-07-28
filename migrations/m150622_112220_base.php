<?php

use yii\db\Migration;

class m150622_112220_base extends Migration
{
    protected $schemaName = 'sync';

    public function safeUp()
    {
        $this->execute('CREATE SCHEMA '.$this->schemaName);

        $cascade = 'ON DELETE CASCADE ON UPDATE CASCADE';
        $restrict = 'ON DELETE RESTRICT ON UPDATE CASCADE';

        $this->createTable("{$this->schemaName}.{{%parser_configurations}}", [
            'id'             => 'pk',
            'name'           => 'string NOT NULL',
            'parser_class'   => 'string NOT NULL',
            'parser_options' => 'jsonb',
            'is_disabled'    => 'boolean NOT NULL DEFAULT FALSE',
            'author_id'      => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'editor_id'      => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'updated_on'     => 'timestamp',
            'created_on'     => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->createTable("{$this->schemaName}.{{%transactions}}", [
            'id'         => 'pk',
            'parser_id'  => "integer NOT NULL REFERENCES {$this->schemaName}.{{%parser_configurations}} (id) $restrict",
            'is_import'  => 'boolean NOT NULL DEFAULT true',
            'author_id'  => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'editor_id'  => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'updated_on' => 'timestamp',
            'created_on' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->createTable("{$this->schemaName}.{{%files}}", [
            'id'             => 'pk',
            'transaction_id' => "integer NOT NULL REFERENCES {$this->schemaName}.{{%transactions}} (id) $restrict",
            'request_id'     => "integer REFERENCES {$this->schemaName}.{{%files}} (id) $cascade",
            'number'         => 'integer NOT NULL DEFAULT 1',
            'url'            => 'string NOT NULL',
            'filename'       => 'string NOT NULL',
            'size'           => 'int NOT NULL',
            'content'        => 'text NOT NULL',
            'mimetype'       => 'string',
            'hash'           => 'string',
            'sent_on'        => 'timestamp',
            'processed_on'   => 'timestamp',
            'acknowledged_on'=> 'timestamp',
            'items_count'    => 'integer',
            'processed_count'=> 'integer',
            'author_id'      => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'editor_id'      => "integer NOT NULL REFERENCES public.{{%users}} (id) $restrict",
            'updated_on'     => 'timestamp',
            'created_on'     => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'UNIQUE (request_id)',
        ]);
        $this->execute("COMMENT ON COLUMN $this->schemaName.{{%files}}.request_id IS "
            . "'null if request, not null if response'");
        $this->createTable("{$this->schemaName}.{{%messages}}", [
            'id'             => 'serial NOT NULL',
            'transaction_id' => "integer NOT NULL REFERENCES {$this->schemaName}.{{%transactions}} (id) $restrict",
            'file_id'        => "integer REFERENCES {$this->schemaName}.{{%files}} (id) $cascade",
            'message'        => 'text NOT NULL',
        ]);

        $indexes = [
            'parser_configurations' => [
                ['class'],
                ['author_id'],
            ],
            'transactions' => [
                ['parser_id'],
                ['is_import'],
                ['author_id'],
            ],
            'files' => [
                ['transaction_id'],
                ['request_id'],
                ['sent_on'],
                ['processed_on'],
                ['acknowledged_on'],
                ['author_id'],
            ],
            'messages' => [
                ['transaction_id'],
                ['file_id'],
            ],
        ];
        foreach ($indexes as $table => $sets) {
            foreach ($sets as $columns) {
                $columns = (array)$columns;
                $this->createIndex(
                    '{{%' . $table . '_' . implode('_', $columns) . '_idx}}',
                    $this->schemaName . '.{{%' . $table . '}}',
                    implode(', ', $columns)
                );
            }
        }

    }

    public function safeDown()
    {
        $this->dropTable("{$this->schemaName}.{{%messages}}");
        $this->dropTable("{$this->schemaName}.{{%files}}");
        $this->dropTable("{$this->schemaName}.{{%transactions}}");
        $this->dropTable("{$this->schemaName}.{{%parser_configurations}}");

        $this->execute('DROP SCHEMA '.$this->schemaName);
    }
}
