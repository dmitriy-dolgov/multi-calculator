<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_active_component}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%component}}`
 * - `{{%unit}}`
 */
class m200802_210238_create_customer_active_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer_active_component}}', [
            'id' => $this->primaryKey(),
            'component_id' => $this->integer(),
            'price_override' => $this->decimal(8,2),
            'price_discount_override' => $this->decimal(8,2),
            'amount' => $this->integer()->notNull()->defaultValue(1),
            'unit_id' => $this->integer(),
            'unit_value' => $this->double(),
            'unit_value_min' => $this->double(),
            'unit_value_max' => $this->double(),
        ]);

        // creates index for column `component_id`
        $this->createIndex(
            '{{%idx-customer_active_component-component_id}}',
            '{{%customer_active_component}}',
            'component_id'
        );

        // add foreign key for table `{{%component}}`
        $this->addForeignKey(
            '{{%fk-customer_active_component-component_id}}',
            '{{%customer_active_component}}',
            'component_id',
            '{{%component}}',
            'id',
            'CASCADE'
        );

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-customer_active_component-unit_id}}',
            '{{%customer_active_component}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-customer_active_component-unit_id}}',
            '{{%customer_active_component}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%component}}`
        $this->dropForeignKey(
            '{{%fk-customer_active_component-component_id}}',
            '{{%customer_active_component}}'
        );

        // drops index for column `component_id`
        $this->dropIndex(
            '{{%idx-customer_active_component-component_id}}',
            '{{%customer_active_component}}'
        );

        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-customer_active_component-unit_id}}',
            '{{%customer_active_component}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-customer_active_component-unit_id}}',
            '{{%customer_active_component}}'
        );

        $this->dropTable('{{%customer_active_component}}');
    }
}
