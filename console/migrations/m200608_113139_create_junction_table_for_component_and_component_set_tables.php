<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%component_component_set}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%component}}`
 * - `{{%component_set}}`
 */
class m200608_113139_create_junction_table_for_component_and_component_set_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%component_component_set}}', [
            'component_id' => $this->integer(),
            'component_set_id' => $this->integer(),
            'PRIMARY KEY(component_id, component_set_id)',
        ]);

        // creates index for column `component_id`
        $this->createIndex(
            '{{%idx-component_component_set-component_id}}',
            '{{%component_component_set}}',
            'component_id'
        );

        // add foreign key for table `{{%component}}`
        $this->addForeignKey(
            '{{%fk-component_component_set-component_id}}',
            '{{%component_component_set}}',
            'component_id',
            '{{%component}}',
            'id',
            'CASCADE'
        );

        // creates index for column `component_set_id`
        $this->createIndex(
            '{{%idx-component_component_set-component_set_id}}',
            '{{%component_component_set}}',
            'component_set_id'
        );

        // add foreign key for table `{{%component_set}}`
        $this->addForeignKey(
            '{{%fk-component_component_set-component_set_id}}',
            '{{%component_component_set}}',
            'component_set_id',
            '{{%component_set}}',
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
            '{{%fk-component_component_set-component_id}}',
            '{{%component_component_set}}'
        );

        // drops index for column `component_id`
        $this->dropIndex(
            '{{%idx-component_component_set-component_id}}',
            '{{%component_component_set}}'
        );

        // drops foreign key for table `{{%component_set}}`
        $this->dropForeignKey(
            '{{%fk-component_component_set-component_set_id}}',
            '{{%component_component_set}}'
        );

        // drops index for column `component_set_id`
        $this->dropIndex(
            '{{%idx-component_component_set-component_set_id}}',
            '{{%component_component_set}}'
        );

        $this->dropTable('{{%component_component_set}}');
    }
}
