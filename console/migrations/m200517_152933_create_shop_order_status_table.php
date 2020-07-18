<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_order_status}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%shop_order}}`
 * - `{{%accepted_by}}`
 */
class m200517_152933_create_shop_order_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_order_status}}', [
            'id' => $this->primaryKey(),
            'shop_order_id' => $this->integer(),
            'type' => $this->string(50)->comment('Тип заказа - принят к исполнению, отложен, начал готовиться, в процессе доставки, отменен, завершен и т. д.'),
            'accepted_at' => $this->datetime()->comment('Время назначения статуса'),
            'accepted_by' => $this->integer()->comment('Сотрудник назначивший статус'),
        ]);

        // creates index for column `shop_order_id`
        $this->createIndex(
            '{{%idx-shop_order_status-shop_order_id}}',
            '{{%shop_order_status}}',
            'shop_order_id'
        );

        // add foreign key for table `{{%shop_order}}`
        $this->addForeignKey(
            '{{%fk-shop_order_status-shop_order_id}}',
            '{{%shop_order_status}}',
            'shop_order_id',
            '{{%shop_order}}',
            'id',
            'CASCADE'
        );

        // creates index for column `accepted_by`
        $this->createIndex(
            '{{%idx-shop_order_status-accepted_by}}',
            '{{%shop_order_status}}',
            'accepted_by'
        );

        // add foreign key for table `{{%accepted_by}}`
        $this->addForeignKey(
            '{{%fk-shop_order_status-accepted_by}}',
            '{{%shop_order_status}}',
            'accepted_by',
            '{{%co_worker}}',
            'id',
            'CASCADE'
        );

        // creates index for column `type`
        $this->createIndex(
            '{{%idx-shop_order_status-type}}',
            '{{%shop_order_status}}',
            'type'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `type`
        $this->dropIndex(
            '{{%idx-shop_order_status-type}}',
            '{{%shop_order_status}}'
        );

        // drops foreign key for table `{{%shop_order}}`
        $this->dropForeignKey(
            '{{%fk-shop_order_status-shop_order_id}}',
            '{{%shop_order_status}}'
        );

        // drops index for column `shop_order_id`
        $this->dropIndex(
            '{{%idx-shop_order_status-shop_order_id}}',
            '{{%shop_order_status}}'
        );

        // drops foreign key for table `{{%accepted_by}}`
        $this->dropForeignKey(
            '{{%fk-shop_order_status-accepted_by}}',
            '{{%shop_order_status}}'
        );

        // drops index for column `accepted_by`
        $this->dropIndex(
            '{{%idx-shop_order_status-accepted_by}}',
            '{{%shop_order_status}}'
        );

        $this->dropTable('{{%shop_order_status}}');
    }
}
