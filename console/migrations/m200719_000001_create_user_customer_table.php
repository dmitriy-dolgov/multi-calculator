<?php

use Da\User\Helper\MigrationHelper;
use yii\db\Migration;

class m200719_000001_create_user_customer_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%user_customer}}',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string(255)->notNull(),
                'email' => $this->string(255)->notNull(),
                'password_hash' => $this->string(60)->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'unconfirmed_email' => $this->string(255),
                'registration_ip' => $this->string(45),
                'flags' => $this->integer()->notNull()->defaultValue('0'),
                'confirmed_at' => $this->integer(),
                'blocked_at' => $this->integer(),
                'updated_at' => $this->integer()->notNull(),
                'created_at' => $this->integer()->notNull(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );

        $this->createIndex('idx_user_customer_username', '{{%user_customer}}', 'username', true);
        $this->createIndex('idx_user_customer_email', '{{%user_customer}}', 'email', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_customer}}');
    }
}
