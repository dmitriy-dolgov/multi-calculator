<?php

use yii\db\Migration;

class m200719_000005_add_last_login_at extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user_customer}}', 'last_login_at', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user_customer}}', 'last_login_at');
    }
}
