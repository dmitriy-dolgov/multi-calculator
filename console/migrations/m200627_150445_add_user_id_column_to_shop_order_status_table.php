<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%shop_order_status}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200627_150445_add_user_id_column_to_shop_order_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_order_status}}', 'user_id', $this->integer()->after('shop_order_id'));

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-shop_order_status-user_id}}',
            '{{%shop_order_status}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-shop_order_status-user_id}}',
            '{{%shop_order_status}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-shop_order_status-user_id}}',
            '{{%shop_order_status}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-shop_order_status-user_id}}',
            '{{%shop_order_status}}'
        );

        $this->dropColumn('{{%shop_order_status}}', 'user_id');
    }
}
