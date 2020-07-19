<?php

use yii\db\Migration;

class m200719_000008_add_last_login_ip extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_customer}}', 'last_login_ip', $this->string(45)->null()->after('last_login_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_customer}}', 'last_login_ip');
    }
}
