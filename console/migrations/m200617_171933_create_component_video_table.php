<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%component_video}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%component}}`
 */
class m200617_171933_create_component_video_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%component_video}}', [
            'id' => $this->primaryKey(),
            'relative_path' => $this->string(255),
            'component_id' => $this->integer(),
            'mime_type' => $this->string(255),
            'deleted_at' => $this->datetime(),
        ]);

        // creates index for column `component_id`
        $this->createIndex(
            '{{%idx-component_video-component_id}}',
            '{{%component_video}}',
            'component_id'
        );

        // add foreign key for table `{{%component}}`
        $this->addForeignKey(
            '{{%fk-component_video-component_id}}',
            '{{%component_video}}',
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
            '{{%fk-component_video-component_id}}',
            '{{%component_video}}'
        );

        // drops index for column `component_id`
        $this->dropIndex(
            '{{%idx-component_video-component_id}}',
            '{{%component_video}}'
        );

        $this->dropTable('{{%component_video}}');
    }
}
