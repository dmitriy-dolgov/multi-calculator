<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_order}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200517_152834_create_shop_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_order}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'created_at' => $this->datetime(),
            'deliver_address' => $this->text()->comment('Куда доставить заказ'),
            'deliver_customer_name' => $this->string(255),
            'deliver_phone' => $this->string(40),
            'deliver_email' => $this->string(255),
            'deliver_comment' => $this->text(),
            'deliver_required_time_begin' => $this->datetime()->comment('Время когда надо доставить заказ - начало'),
            'deliver_required_time_end' => $this->datetime()->comment('Время когда надо доставить заказ - конец'),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-shop_order-user_id}}',
            '{{%shop_order}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-shop_order-user_id}}',
            '{{%shop_order}}',
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
            '{{%fk-shop_order-user_id}}',
            '{{%shop_order}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-shop_order-user_id}}',
            '{{%shop_order}}'
        );

        $this->dropTable('{{%shop_order}}');
    }
}
