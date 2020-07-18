<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unit}}`.
 */
class m200619_064222_create_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'short_name' => $this->string(20),
            'symbol' => $this->string(10),
            'symbol_pattern' => $this->string(40)->comment('Шаблон, демонстрирующий значение вместе со знаком единицы'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unit}}');
    }
}
