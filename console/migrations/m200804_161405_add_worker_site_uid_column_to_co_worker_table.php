<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%co_worker}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%co_worker_function}}`
 */
class m200804_161405_add_worker_site_uid_column_to_co_worker_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%co_worker}}', 'co_worker_function', $this->string(70));
        $this->addColumn('{{%co_worker}}', 'description', $this->text());
        $this->addColumn('{{%co_worker}}', 'worker_site_uid', $this->string(20)->comment('Уникальный ID для доступа к панели с доступными ползьователю функциями'));

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

    /**
     * {@inheritdoc}
     */
    public function safeDown()
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
        $this->dropColumn('{{%co_worker}}', 'description');
        $this->dropColumn('{{%co_worker}}', 'worker_site_uid');
    }
}
