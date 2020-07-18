<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%component_switch_group}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200619_121358_create_component_switch_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%component_switch_group}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(255),
            'description' => $this->text(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-component_switch_group-user_id}}',
            '{{%component_switch_group}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-component_switch_group-user_id}}',
            '{{%component_switch_group}}',
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
            '{{%fk-component_switch_group-user_id}}',
            '{{%component_switch_group}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-component_switch_group-user_id}}',
            '{{%component_switch_group}}'
        );

        $this->dropTable('{{%component_switch_group}}');
    }
}
