<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%component_image}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%component}}`
 */
class m190905_142826_create_component_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%component_image}}', [
            'id' => $this->primaryKey(),
            'component_id' => $this->integer(),
            'relative_path' => $this->string(255),
            'deleted_at' => $this->datetime(),
        ]);

        // creates index for column `component_id`
        /*$this->createIndex(
            '{{%idx-component_image-component_id}}',
            '{{%component_image}}',
            'component_id'
        );*/

        //TODO: подумать верно ли, может нужен индекс для `component_id`
        $this->createIndex(
            '{{%idx-component_image-component_id-relative_path}}',
            '{{%component_image}}',
            ['component_id', 'relative_path']
        );

        // add foreign key for table `{{%component}}`
        $this->addForeignKey(
            '{{%fk-component_image-component_id}}',
            '{{%component_image}}',
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
        // drops foreign key for table `{{%component}}`
        $this->dropForeignKey(
            '{{%fk-component_image-component_id}}',
            '{{%component_image}}'
        );

        // drops index for column `component_id`
        /*$this->dropIndex(
            '{{%idx-component_image-component_id}}',
            '{{%component_image}}'
        );*/

        $this->dropIndex(
            '{{%idx-component_image-component_id-relative_path}}',
            '{{%component_image}}'
        );

        $this->dropTable('{{%component_image}}');
    }
}
