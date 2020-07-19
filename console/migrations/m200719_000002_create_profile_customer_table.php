<?php

use Da\User\Helper\MigrationHelper;
use yii\db\Migration;

class m200719_000002_create_profile_customer_table extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%profile_customer}}',
            [
                'user_id' => $this->integer()->notNull(),
                'name' => $this->string(255),
                'public_email' => $this->string(255),
                'gravatar_email' => $this->string(255),
                'gravatar_id' => $this->string(32),
                'location' => $this->string(255),
                'website' => $this->string(255),
                'timezone' => $this->string(40),
                'bio' => $this->text(),
            ],
            MigrationHelper::resolveTableOptions($this->db->driverName)
        );

        $this->addPrimaryKey('{{%profile_customer_pk}}', '{{%profile_customer}}', 'user_id');

        $restrict = MigrationHelper::isMicrosoftSQLServer($this->db->driverName) ? 'NO ACTION' : 'RESTRICT';

        $this->addForeignKey('fk_profile_customer_user', '{{%profile_customer}}', 'user_id', '{{%user_customer}}', 'id', 'CASCADE', $restrict);
    }

    public function safeDown()
    {
        $this->dropTable('{{%profile_customer}}');
    }
}
