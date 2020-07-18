<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%co_worker}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200517_150323_create_co_worker_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%co_worker}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(255),
            'birthday' => $this->datetime(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-co_worker-user_id}}',
            '{{%co_worker}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-co_worker-user_id}}',
            '{{%co_worker}}',
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
            '{{%fk-co_worker-user_id}}',
            '{{%co_worker}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-co_worker-user_id}}',
            '{{%co_worker}}'
        );

        $this->dropTable('{{%co_worker}}');
    }
}
