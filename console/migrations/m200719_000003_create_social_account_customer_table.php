<?php

use Da\User\Helper\MigrationHelper;
use yii\db\Migration;

class m200719_000003_create_social_account_customer_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%social_account_customer}}',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer(),
                'provider' => $this->string(255)->notNull(),
                'client_id' => $this->string(255)->notNull(),
                'code' => $this->string(32),
                'email' => $this->string(255),
                'username' => $this->string(255),
                'data' => $this->text(),
                'created_at' => $this->integer(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );

        $this->createIndex(
            'idx_social_account_customer_provider_client_id',
            '{{%social_account_customer}}',
            ['provider', 'client_id'],
            true
        );

        $this->createIndex('idx_social_account_customer_code', '{{%social_account_customer}}', 'code', true);

        $this->addForeignKey(
            'fk_social_account_customer_user',
            '{{%social_account_customer}}',
            'user_id',
            '{{%user_customer}}',
            'id',
            'CASCADE',
            (MigrationHelper::isMicrosoftSQLServer($this->db->driverName) ? 'NO ACTION' : 'RESTRICT')
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%social_account_customer}}');
    }
}
