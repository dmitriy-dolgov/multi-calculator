<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_virtual}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210416_180456_create_user_virtual_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_virtual}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_virtual-user_id}}',
            '{{%user_virtual}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_virtual-user_id}}',
            '{{%user_virtual}}',
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
            '{{%fk-user_virtual-user_id}}',
            '{{%user_virtual}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_virtual-user_id}}',
            '{{%user_virtual}}'
        );

        $this->dropTable('{{%user_virtual}}');
    }
}
