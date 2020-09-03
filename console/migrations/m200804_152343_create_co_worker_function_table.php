<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%co_worker_function}}`.
 */
class m200804_152343_create_co_worker_function_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%co_worker_function}}', [
            'id' => $this->string(70)->unique()->notNull(),
            'name' => $this->string(255),
            'description' => $this->text(),
        ]);

        $this->createIndex(
            '{{%idx-co_worker_function-id}}',
            '{{%co_worker_function}}',
            'id'
        );

        $this->insert('{{%co_worker_function}}', [
            'id' => 'super_user',
            'name' => 'Chief Administrator',
        ]);
        $this->insert('{{%co_worker_function}}', [
            'id' => 'component_manager',
            'name' => 'Component Manager',
        ]);
        $this->insert('{{%co_worker_function}}', [
            'id' => 'maker',
            'name' => 'Maker',
        ]);
        $this->insert('{{%co_worker_function}}', [
            'id' => 'courier',
            'name' => 'Сourier',
        ]);
        $this->insert('{{%co_worker_function}}', [
            'id' => 'accept_orders',
            'name' => 'Accept orders',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-co_worker_function-id}}',
            '{{%co_worker_function}}'
        );

        $this->dropTable('{{%co_worker_function}}');
    }
}
