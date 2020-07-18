<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%location}}`
 * - `{{%category}}`
 */
class m190905_142610_create_shop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'location_id' => $this->integer(),
            'top_category_id' => $this->integer()->comment('Указывает на коренную категорию через которую идет привязка более низовых категорий и компонентов'),
            'deleted_at' => $this->datetime(),
        ]);

        // creates index for column `location_id`
        $this->createIndex(
            '{{%idx-shop-location_id}}',
            '{{%shop}}',
            'location_id'
        );

        // add foreign key for table `{{%location}}`
        $this->addForeignKey(
            '{{%fk-shop-location_id}}',
            '{{%shop}}',
            'location_id',
            '{{%location}}',
            'id',
            'CASCADE'
        );

        // creates index for column `top_category_id`
        $this->createIndex(
            '{{%idx-shop-top_category_id}}',
            '{{%shop}}',
            'top_category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-shop-top_category_id}}',
            '{{%shop}}',
            'top_category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%location}}`
        $this->dropForeignKey(
            '{{%fk-shop-location_id}}',
            '{{%shop}}'
        );

        // drops index for column `location_id`
        $this->dropIndex(
            '{{%idx-shop-location_id}}',
            '{{%shop}}'
        );

        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-shop-top_category_id}}',
            '{{%shop}}'
        );

        // drops index for column `top_category_id`
        $this->dropIndex(
            '{{%idx-shop-top_category_id}}',
            '{{%shop}}'
        );

        $this->dropTable('{{%shop}}');
    }
}
