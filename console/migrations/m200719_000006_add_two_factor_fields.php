<?php

use Da\User\Helper\MigrationHelper;
use yii\db\Migration;

class m200719_000006_add_two_factor_fields extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user_customer}}', 'auth_tf_key', $this->string(16));
        $this->addColumn(
            '{{%user_customer}}',
            'auth_tf_enabled',
            $this->boolean()->defaultValue(MigrationHelper::getBooleanValue($this->db->driverName))
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user_customer}}', 'auth_tf_key');
        $this->dropColumn('{{%user_customer}}', 'auth_tf_enabled');
    }
}
