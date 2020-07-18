<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%component}}`.
 */
class m200619_171616_add_item_select_max_column_to_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%component}}', 'item_select_min', $this->integer()->unsigned()->after('amount')->comment('Мин. количество выбираемых компонентов'));
        $this->addColumn('{{%component}}', 'item_select_max', $this->integer()->unsigned()->after('item_select_min')->comment('Макс. количество выбираемых компонентов'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%component}}', 'item_select_min');
        $this->dropColumn('{{%component}}', 'item_select_max');
    }
}
