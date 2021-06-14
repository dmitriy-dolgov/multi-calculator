<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_virtual}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210612_185355_create_customer_virtual_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer_virtual}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-customer_virtual-user_id}}',
            '{{%customer_virtual}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-customer_virtual-user_id}}',
            '{{%customer_virtual}}',
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
            '{{%fk-customer_virtual-user_id}}',
            '{{%customer_virtual}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-customer_virtual-user_id}}',
            '{{%customer_virtual}}'
        );

        $this->dropTable('{{%customer_virtual}}');
    }
}
