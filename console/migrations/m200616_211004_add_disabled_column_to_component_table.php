<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%component}}`.
 */
class m200616_211004_add_disabled_column_to_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%component}}', 'disabled', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%component}}', 'disabled');
    }
}
