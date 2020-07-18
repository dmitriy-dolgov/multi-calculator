<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_order_components}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%shop_order}}`
 * - `{{%component}}`
 */
class m200517_154029_create_shop_order_components_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_order_components}}', [
            'id' => $this->primaryKey(),
            'shop_order_id' => $this->integer(),
            'component_id' => $this->integer(),
            'name' => $this->string(255),
            'short_name' => $this->string(255),
            'order_price' => $this->decimal(8,2),
            'order_price_discount' => $this->decimal(8,2),
        ]);

        // creates index for column `shop_order_id`
        $this->createIndex(
            '{{%idx-shop_order_components-shop_order_id}}',
            '{{%shop_order_components}}',
            'shop_order_id'
        );

        // add foreign key for table `{{%shop_order}}`
        $this->addForeignKey(
            '{{%fk-shop_order_components-shop_order_id}}',
            '{{%shop_order_components}}',
            'shop_order_id',
            '{{%shop_order}}',
            'id',
            'CASCADE'
        );

        // creates index for column `component_id`
        $this->createIndex(
            '{{%idx-shop_order_components-component_id}}',
            '{{%shop_order_components}}',
            'component_id'
        );

        // add foreign key for table `{{%component}}`
        $this->addForeignKey(
            '{{%fk-shop_order_components-component_id}}',
            '{{%shop_order_components}}',
            'component_id',
            '{{%component}}',
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
            '{{%fk-shop_order_components-shop_order_id}}',
            '{{%shop_order_components}}'
        );

        // drops index for column `shop_order_id`
        $this->dropIndex(
            '{{%idx-shop_order_components-shop_order_id}}',
            '{{%shop_order_components}}'
        );

        // drops foreign key for table `{{%component}}`
        $this->dropForeignKey(
            '{{%fk-shop_order_components-component_id}}',
            '{{%shop_order_components}}'
        );

        // drops index for column `component_id`
        $this->dropIndex(
            '{{%idx-shop_order_components-component_id}}',
            '{{%shop_order_components}}'
        );

        $this->dropTable('{{%shop_order_components}}');
    }
}
