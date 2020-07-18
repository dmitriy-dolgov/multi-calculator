<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%shop_order}}`.
 */
class m200609_151021_add_order_uid_column_to_shop_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_order}}', 'order_uid', $this->string(50)->after('user_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_order}}', 'order_uid');
    }
}
