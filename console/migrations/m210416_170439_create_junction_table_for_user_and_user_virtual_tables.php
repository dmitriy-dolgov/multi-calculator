<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_user_virtual}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user_virtual}}`
 */
class m210416_170439_create_junction_table_for_user_and_user_virtual_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_user_virtual}}', [
            'user_id' => $this->integer(),
            'user_virtual_id' => $this->integer(),
            'PRIMARY KEY(user_id, user_virtual_id)',
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_user_virtual-user_id}}',
            '{{%user_user_virtual}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_user_virtual-user_id}}',
            '{{%user_user_virtual}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_virtual_id`
        $this->createIndex(
            '{{%idx-user_user_virtual-user_virtual_id}}',
            '{{%user_user_virtual}}',
            'user_virtual_id'
        );

        // add foreign key for table `{{%user_virtual}}`
        $this->addForeignKey(
            '{{%fk-user_user_virtual-user_virtual_id}}',
            '{{%user_user_virtual}}',
            'user_virtual_id',
            '{{%user_virtual}}',
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
            '{{%fk-user_user_virtual-user_id}}',
            '{{%user_user_virtual}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_user_virtual-user_id}}',
            '{{%user_user_virtual}}'
        );

        // drops foreign key for table `{{%user_virtual}}`
        $this->dropForeignKey(
            '{{%fk-user_user_virtual-user_virtual_id}}',
            '{{%user_user_virtual}}'
        );

        // drops index for column `user_virtual_id`
        $this->dropIndex(
            '{{%idx-user_user_virtual-user_virtual_id}}',
            '{{%user_user_virtual}}'
        );

        $this->dropTable('{{%user_user_virtual}}');
    }
}
