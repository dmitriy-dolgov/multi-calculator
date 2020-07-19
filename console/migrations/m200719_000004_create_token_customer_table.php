<?php

use Da\User\Helper\MigrationHelper;
use yii\db\Migration;

class m200719_000004_create_token_customer_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%token_customer}}',
            [
                'user_id' => $this->integer(),
                'code' => $this->string(32)->notNull(),
                'type' => $this->smallInteger(6)->notNull(),
                'created_at' => $this->integer()->notNull(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );

        $this->createIndex('idx_token_customer_user_id_code_type', '{{%token_customer}}', ['user_id', 'code', 'type'], true);

        $restrict = MigrationHelper::isMicrosoftSQLServer($this->db->driverName) ? 'NO ACTION' : 'RESTRICT';

        $this->addForeignKey('fk_token_customer_user', '{{%token_customer}}', 'user_id', '{{%user_customer}}', 'id', 'CASCADE', $restrict);
    }

    public function safeDown()
    {
        $this->dropTable('{{%token_customer}}');
    }
}
