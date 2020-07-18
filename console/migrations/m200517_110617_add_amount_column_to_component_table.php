<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%component}}`.
 */
class m200517_110617_add_amount_column_to_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%component}}', 'amount', $this->integer()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%component}}', 'amount');
    }
}
