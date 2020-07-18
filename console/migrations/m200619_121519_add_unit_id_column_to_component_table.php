<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%component}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%unit}}`
 * - `{{%component_switch_group}}`
 */
class m200619_121519_add_unit_id_column_to_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%component}}', 'unit_id', $this->integer()->after('amount'));
        $this->addColumn('{{%component}}', 'unit_value', $this->double()->after('unit_id'));
        $this->addColumn('{{%component}}', 'unit_value_min', $this->double()->after('unit_value'));
        $this->addColumn('{{%component}}', 'unit_value_max', $this->double()->after('unit_value_min'));
        $this->addColumn('{{%component}}', 'unit_switch_group', $this->integer()->after('unit_value_max'));

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-component-unit_id}}',
            '{{%component}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-component-unit_id}}',
            '{{%component}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'CASCADE'
        );

        // creates index for column `unit_switch_group`
        $this->createIndex(
            '{{%idx-component-unit_switch_group}}',
            '{{%component}}',
            'unit_switch_group'
        );

        // add foreign key for table `{{%component_switch_group}}`
        $this->addForeignKey(
            '{{%fk-component-unit_switch_group}}',
            '{{%component}}',
            'unit_switch_group',
            '{{%component_switch_group}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-component-unit_id}}',
            '{{%component}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-component-unit_id}}',
            '{{%component}}'
        );

        // drops foreign key for table `{{%component_switch_group}}`
        $this->dropForeignKey(
            '{{%fk-component-unit_switch_group}}',
            '{{%component}}'
        );

        // drops index for column `unit_switch_group`
        $this->dropIndex(
            '{{%idx-component-unit_switch_group}}',
            '{{%component}}'
        );

        $this->dropColumn('{{%component}}', 'unit_id');
        $this->dropColumn('{{%component}}', 'unit_value');
        $this->dropColumn('{{%component}}', 'unit_value_min');
        $this->dropColumn('{{%component}}', 'unit_value_max');
        $this->dropColumn('{{%component}}', 'unit_switch_group');
    }
}
