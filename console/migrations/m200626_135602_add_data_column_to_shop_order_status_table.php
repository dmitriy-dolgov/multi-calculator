<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%shop_order_status}}`.
 */
class m200626_135602_add_data_column_to_shop_order_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_order_status}}', 'data', $this->text()->comment('Та или иная дополнительная информация (например, заказ создан для одной пиццерии или для нескольких)'));
        $this->addColumn('{{%shop_order_status}}', 'description', $this->text()->comment('Описание, которое, возможно, захочет добавить тот кто принял заказ или заказчик'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_order_status}}', 'data');
        $this->dropColumn('{{%shop_order_status}}', 'description');
    }
}
