<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%profile}}`.
 */
class m200711_162735_add_map_icon_column_to_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%profile}}', 'icon_name', $this->string(70)->comment('Название изображения иконки в icomoon'));
        $this->addColumn('{{%profile}}', 'icon_color', $this->string(255)->comment('Цвет иконки'));
        $this->addColumn('{{%profile}}', 'icon_image_path', $this->string(255)->comment('Относительный путь к изображению иконки'));
        $this->addColumn('{{%profile}}', 'facility_image_path', $this->string(255)->comment('Относительный путь к изображению заведения'));
        $this->addColumn('{{%profile}}', 'schedule', $this->text()->comment('Расписание работы'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%profile}}', 'icon_name');
        $this->dropColumn('{{%profile}}', 'icon_color');
        $this->dropColumn('{{%profile}}', 'icon_image_path');
        $this->dropColumn('{{%profile}}', 'facility_image_path');
        $this->dropColumn('{{%profile}}', 'schedule');
    }
}
