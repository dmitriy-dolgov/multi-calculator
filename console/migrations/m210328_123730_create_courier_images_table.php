<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%courier_images}}`.
 */
class m210328_123730_create_courier_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%courier_images}}', [
            'id' => $this->primaryKey(),
            'run' => $this->string(255)->comment('Название изображения "Курьер в движении"'),
            'wait' => $this->string(255)->comment('Название изображения "Курьер в ожидании покупателя чтобы отдать пиццу"'),
            'disabled_at' => $this->datetime()->comment('Когда изображение стало неактивным - действует как расширенный датой boolean'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%courier_images}}');
    }
}
