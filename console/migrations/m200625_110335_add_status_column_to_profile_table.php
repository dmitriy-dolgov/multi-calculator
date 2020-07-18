<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%profile}}`.
 */
class m200625_110335_add_status_column_to_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profile}}', 'status', $this->string(40)->comment('Статус: активен, тест или др.'));

        // creates index for column `status`
        $this->createIndex(
            '{{%idx-profile-status}}',
            '{{%profile}}',
            'status'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `status`
        $this->dropIndex(
            '{{%idx-profile-status}}',
            '{{%profile}}'
        );

        $this->dropColumn('{{%profile}}', 'status');
    }
}
