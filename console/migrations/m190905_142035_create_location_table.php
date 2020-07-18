<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location}}`.
 */
class m190905_142035_create_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%location}}', [
            'id' => $this->primaryKey(),
            'region' => $this->string(255)->comment('Регион (область)'),
            'region_id' => $this->string(255)->comment('Код региона (области)'),
            'district' => $this->string(255)->comment('Район'),
            'district_id' => $this->string(255)->comment('Код района'),
            'city' => $this->string(255)->comment('Город (населённый пункт)'),
            'city_id' => $this->string(255)->comment('Код города (населённого пункта)'),
            'street' => $this->string(255)->comment('Улица'),
            'street_id' => $this->string(255)->comment('Код улицы'),
            'building' => $this->string(255)->comment('Дом (строение)'),
            'building_id' => $this->string(255)->comment('Код дома (строения)'),
            'appartment' => $this->string(255)->comment('Помещение'),
            'zip_code' => $this->string(255)->comment('Почтовый индекс'),
            'arbitrary_address' => $this->string(255)->comment('Произвольная строка адреса'),
            'deleted_at' => $this->datetime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%location}}');
    }
}
