<?php

use yii\db\Migration;

/**
 * Class m200703_161742_alter_type_column_to_shop_order_status_table
 */
class m200703_161742_alter_type_column_to_shop_order_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('shop_order_status', 'type',
            $this->string(255)->comment('Тип заказа - принят к исполнению, отложен, начал готовиться, в процессе доставки, отменен, завершен и т. д.'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200703_161742_alter_type_column_to_shop_order_status_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200703_161742_alter_type_column_to_shop_order_status_table cannot be reverted.\n";

        return false;
    }
    */
}
