<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_order_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%shop_order}}`
 * - `{{%user}}`
 */
class m200626_171106_create_junction_table_for_shop_order_and_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_order_user}}', [
            'shop_order_id' => $this->integer(),
            'user_id' => $this->integer(),
            'PRIMARY KEY(shop_order_id, user_id)',
        ]);

        // creates index for column `shop_order_id`
        $this->createIndex(
            '{{%idx-shop_order_user-shop_order_id}}',
            '{{%shop_order_user}}',
            'shop_order_id'
        );

        // add foreign key for table `{{%shop_order}}`
        $this->addForeignKey(
            '{{%fk-shop_order_user-shop_order_id}}',
            '{{%shop_order_user}}',
            'shop_order_id',
            '{{%shop_order}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-shop_order_user-user_id}}',
            '{{%shop_order_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-shop_order_user-user_id}}',
            '{{%shop_order_user}}',
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
        // drops foreign key for table `{{%shop_order}}`
        $this->dropForeignKey(
            '{{%fk-shop_order_user-shop_order_id}}',
            '{{%shop_order_user}}'
        );

        // drops index for column `shop_order_id`
        $this->dropIndex(
            '{{%idx-shop_order_user-shop_order_id}}',
            '{{%shop_order_user}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-shop_order_user-user_id}}',
            '{{%shop_order_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-shop_order_user-user_id}}',
            '{{%shop_order_user}}'
        );

        $this->dropTable('{{%shop_order_user}}');
    }
}
