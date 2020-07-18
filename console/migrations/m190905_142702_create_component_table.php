<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%component}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%component}}`
 * - `{{%category}}`
 */
class m190905_142702_create_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%component}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'short_name' => $this->string(255)->comment('Короткая версия названия'),
            'parent_component_id' => $this->integer()->comment('Родительский элемент, если есть - значит этот компонент может являться частью другого компонента'),
            'category_id' => $this->integer(),  //TODO: сделать привязку ко многим категориям
            'deleted_at' => $this->datetime(),
        ]);

        // creates index for column `parent_component_id`
        $this->createIndex(
            '{{%idx-component-parent_component_id}}',
            '{{%component}}',
            'parent_component_id'
        );

        // add foreign key for table `{{%component}}`
        $this->addForeignKey(
            '{{%fk-component-parent_component_id}}',
            '{{%component}}',
            'parent_component_id',
            '{{%component}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-component-category_id}}',
            '{{%component}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-component-category_id}}',
            '{{%component}}',
            'category_id',
            '{{%category}}',
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
            '{{%fk-component-parent_component_id}}',
            '{{%component}}'
        );

        // drops index for column `parent_component_id`
        $this->dropIndex(
            '{{%idx-component-parent_component_id}}',
            '{{%component}}'
        );

        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-component-category_id}}',
            '{{%component}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-component-category_id}}',
            '{{%component}}'
        );

        $this->dropTable('{{%component}}');
    }
}
