<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%geopoint}}`.
 */
class m210601_144232_create_geopoint_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%geopoint}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->defaultValue('No name'),
            'region' => $this->string(255),
            'sub_region' => $this->string(255),
            'code_cdek' => $this->string(40)->unique(),
            'kladr_code' => $this->string(40),
            'uuid' => $this->string(255),
            'fias_uuid' => $this->string(255),
            'country' => $this->string(70),
            'region_code' => $this->string(40),
            'lat_long' => $this->string(60)->comment('Координаты цента пункта в десятичных градусах'),
            'merchant_coverage_radius' => $this->float()->comment('Радиус доступного расположения продавцов от середины (км)'),
            'index' => $this->string(40)->comment('Почтовый индекс'),
            'code_boxberry' => $this->string(255),
            'code_dpd' => $this->string(40),
        ]);

        $this->createIndex(
            '{{%idx-geopoint-code_boxberry}}',
            '{{%geopoint}}',
            'code_boxberry'
        );

        $this->createIndex(
            '{{%idx-geopoint-code_dpd}}',
            '{{%geopoint}}',
            'code_dpd'
        );

        $this->insert('geopoint',
            [

            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-geopoint-code_dpd}}',
            '{{%geopoint}}'
        );

        $this->dropIndex(
            '{{%idx-geopoint-code_boxberry}}',
            '{{%geopoint}}'
        );

        $this->dropTable('{{%geopoint}}');
    }
}
