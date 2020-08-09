<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%co_worker_decline_cause}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%co_worker}}`
 */
class m200809_130102_create_co_worker_decline_cause_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%co_worker_decline_cause}}', [
            'id' => $this->primaryKey(),
            'co_worker_id' => $this->integer(),
            'cause' => $this->text()->notNull(),
            'order' => $this->integer(),
        ]);

        // creates index for column `co_worker_id`
        $this->createIndex(
            '{{%idx-co_worker_decline_cause-co_worker_id}}',
            '{{%co_worker_decline_cause}}',
            'co_worker_id'
        );

        // add foreign key for table `{{%co_worker}}`
        $this->addForeignKey(
            '{{%fk-co_worker_decline_cause-co_worker_id}}',
            '{{%co_worker_decline_cause}}',
            'co_worker_id',
            '{{%co_worker}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%co_worker}}`
        $this->dropForeignKey(
            '{{%fk-co_worker_decline_cause-co_worker_id}}',
            '{{%co_worker_decline_cause}}'
        );

        // drops index for column `co_worker_id`
        $this->dropIndex(
            '{{%idx-co_worker_decline_cause-co_worker_id}}',
            '{{%co_worker_decline_cause}}'
        );

        $this->dropTable('{{%co_worker_decline_cause}}');
    }
}
