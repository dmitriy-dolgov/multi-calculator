<?php

use yii\db\Migration;

/**
 * Class m200814_155852_alter_shop_order_components_and_shop_order_status_tables
 */
class m200814_155852_alter_shop_order_components_and_shop_order_status_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%shop_order_components}}', 'shop_order_id', $this->integer()->notNull());

        $this->alterColumn('{{%shop_order_status}}', 'shop_order_id', $this->integer()->notNull());
        $this->alterColumn('{{%shop_order_status}}', 'user_id', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m200814_155852_alter_shop_order_components_and_shop_order_status_tables cannot be reverted.\n";
        //return false;

        $this->alterColumn('{{%shop_order_components}}', 'shop_order_id', $this->integer());

        $this->alterColumn('{{%shop_order_status}}', 'shop_order_id', $this->integer());
        $this->alterColumn('{{%shop_order_status}}', 'user_id', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200814_155852_alter_shop_order_components_and_shop_order_status_tables cannot be reverted.\n";

        return false;
    }
    */
}
