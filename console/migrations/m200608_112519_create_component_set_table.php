<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%component_set}}`.
 */
class m200608_112519_create_component_set_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%component_set}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%component_set}}');
    }
}
