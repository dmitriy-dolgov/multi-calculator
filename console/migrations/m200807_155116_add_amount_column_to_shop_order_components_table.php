<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%shop_order_components}}`.
 */
class m200807_155116_add_amount_column_to_shop_order_components_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_order_components}}', 'amount', $this->integer()->unsigned()->defaultValue(1)->after('short_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_order_components}}', 'amount');
    }
}
