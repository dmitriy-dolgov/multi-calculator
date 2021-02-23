<?php

use yii\db\Migration;

/**
 * Class m210223_145336_change_order_status_type
 */
class m210223_145336_change_order_status_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('shop_order_status', ['type' => 'blocked-with-another-pizzeria'], ['type' => 'blocked-with-other-pizzeria']);
        $this->update('shop_order_status', ['type' => 'accepted-by-maker'], ['type' => 'offer-accepted-by-cook']);
        $this->update('shop_order_status', ['type' => 'accepted-by-maker'], ['type' => 'offer-sent-to-cook']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210223_145336_change_order_status_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_145336_change_order_status_type cannot be reverted.\n";

        return false;
    }
    */
}
