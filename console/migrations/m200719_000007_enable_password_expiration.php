<?php

use yii\db\Migration;

class m200719_000007_enable_password_expiration extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user_customer}}', 'password_changed_at', $this->integer()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user_customer}}', 'password_changed_at');
    }
}
