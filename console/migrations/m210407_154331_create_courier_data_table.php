<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%courier_data}}`.
 */
class m210407_154331_create_courier_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%courier_data}}', [
            'id' => $this->primaryKey(),
            'name_of_courier' => $this->string(255),
            'description_of_courier' => $this->text(),
            'photo_of_courier' => $this->string(255),
            'courier_in_move' => $this->string(255)->comment('Название изображения курьера в движении'),
            'courier_is_waiting' => $this->string(255)->comment('Название изображения курьера в ожидании - например ждет клиента'),
            'velocity' => $this->integer()->defaultValue(5)->comment('Средняя скорость курьера - км/час'),
            'priority' => $this->integer()->notNull()->defaultValue(0)->comment('Приоритет при любом статусе - например при случайном выборе - чем выше тем больше.'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%courier_data}}');
    }
}
