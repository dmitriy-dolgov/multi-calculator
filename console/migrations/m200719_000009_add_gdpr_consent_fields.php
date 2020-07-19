<?php

use Da\User\Helper\MigrationHelper;
use yii\db\Migration;

class m200719_000009_add_gdpr_consent_fields extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user_customer}}', 'gdpr_consent', $this->boolean()->defaultValue(MigrationHelper::getBooleanValue($this->db->driverName, false)));
        $this->addColumn('{{%user_customer}}', 'gdpr_consent_date', $this->integer(11)->null());
        $this->addColumn('{{%user_customer}}', 'gdpr_deleted', $this->boolean()->defaultValue(MigrationHelper::getBooleanValue($this->db->driverName, false)));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user_customer}}', 'gdpr_consent');
        $this->dropColumn('{{%user_customer}}', 'gdpr_consent_date');
        $this->dropColumn('{{%user_customer}}', 'gdpr_deleted');
    }
}
