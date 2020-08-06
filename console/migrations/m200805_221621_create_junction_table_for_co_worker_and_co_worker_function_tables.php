<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%co_worker_co_worker_function}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%co_worker}}`
 * - `{{%co_worker_function}}`
 */
class m200805_221621_create_junction_table_for_co_worker_and_co_worker_function_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%co_worker_co_worker_function}}', [
            'co_worker_id' => $this->integer()->notNull(),
            //'co_worker_function_id' => $this->integer(),
            'co_worker_function_id' => $this->string(70)->notNull(),
            'PRIMARY KEY(co_worker_id, co_worker_function_id)',
        ]);

        // creates index for column `co_worker_id`
        $this->createIndex(
            '{{%idx-co_worker_co_worker_function-co_worker_id}}',
            '{{%co_worker_co_worker_function}}',
            'co_worker_id'
        );

        // add foreign key for table `{{%co_worker}}`
        $this->addForeignKey(
            '{{%fk-co_worker_co_worker_function-co_worker_id}}',
            '{{%co_worker_co_worker_function}}',
            'co_worker_id',
            '{{%co_worker}}',
            'id',
            'CASCADE'
        );

        // creates index for column `co_worker_function_id`
        $this->createIndex(
            '{{%idx-co_worker_co_worker_function-co_worker_function_id}}',
            '{{%co_worker_co_worker_function}}',
            'co_worker_function_id'
        );

        // add foreign key for table `{{%co_worker_function}}`
        $this->addForeignKey(
            '{{%fk-co_worker_co_worker_function-co_worker_function_id}}',
            '{{%co_worker_co_worker_function}}',
            'co_worker_function_id',
            '{{%co_worker_function}}',
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
            '{{%fk-co_worker_co_worker_function-co_worker_id}}',
            '{{%co_worker_co_worker_function}}'
        );

        // drops index for column `co_worker_id`
        $this->dropIndex(
            '{{%idx-co_worker_co_worker_function-co_worker_id}}',
            '{{%co_worker_co_worker_function}}'
        );

        // drops foreign key for table `{{%co_worker_function}}`
        $this->dropForeignKey(
            '{{%fk-co_worker_co_worker_function-co_worker_function_id}}',
            '{{%co_worker_co_worker_function}}'
        );

        // drops index for column `co_worker_function_id`
        $this->dropIndex(
            '{{%idx-co_worker_co_worker_function-co_worker_function_id}}',
            '{{%co_worker_co_worker_function}}'
        );

        $this->dropTable('{{%co_worker_co_worker_function}}');
    }
}
