<?php

use yii\db\Migration;

/**
 * Class m200903_112823_update_co_worker_function_table_rows
 */
class m200903_112823_update_co_worker_function_table_rows extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('co_worker_co_worker_function', ['co_worker_function_id' => 'maker']);

        $this->update('co_worker_function',
            ['id' => 'maker', 'name' => 'Maker'],
            ['id' => 'maker']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200903_112823_update_co_worker_function_table_rows cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200903_112823_update_co_worker_function_table_rows cannot be reverted.\n";

        return false;
    }
    */
}
