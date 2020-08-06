<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%co_worker}}`.
 */
class m200805_224559_drop_co_worker_function_column_from_co_worker_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%co_worker_function}}`
        $this->dropForeignKey(
            '{{%fk-co_worker-co_worker_function}}',
            '{{%co_worker}}'
        );

        // drops index for column `co_worker_function`
        $this->dropIndex(
            '{{%idx-co_worker-co_worker_function}}',
            '{{%co_worker}}'
        );

        $this->dropColumn('{{%co_worker}}', 'co_worker_function');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /*echo "m200805_224559_drop_co_worker_function_column_from_co_worker_table cannot be reverted.\n";

        return false;*/

        $this->addColumn('{{%co_worker}}', 'co_worker_function', $this->string(70)->after('name'));

        // creates index for column `co_worker_function`
        $this->createIndex(
            '{{%idx-co_worker-co_worker_function}}',
            '{{%co_worker}}',
            'co_worker_function'
        );

        // add foreign key for table `{{%co_worker_function}}`
        $this->addForeignKey(
            '{{%fk-co_worker-co_worker_function}}',
            '{{%co_worker}}',
            'co_worker_function',
            '{{%co_worker_function}}',
            'id',
            'SET NULL'
        );
    }
}
