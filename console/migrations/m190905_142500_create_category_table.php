<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%parent_category}}`
 */
class m190905_142500_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'short_name' => $this->string(255),
            'description' => $this->string(255)->comment('Подробное описание'),
            'parent_category_id' => $this->integer(),
            'deleted_at' => $this->datetime(),
        ]);

        // creates index for column `parent_category_id`
        $this->createIndex(
            '{{%idx-category-parent_category_id}}',
            '{{%category}}',
            'parent_category_id'
        );

        // add foreign key for table `{{%parent_category}}`
        $this->addForeignKey(
            '{{%fk-category-parent_category_id}}',
            '{{%category}}',
            'parent_category_id',
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
        // drops foreign key for table `{{%parent_category}}`
        $this->dropForeignKey(
            '{{%fk-category-parent_category_id}}',
            '{{%category}}'
        );

        // drops index for column `parent_category_id`
        $this->dropIndex(
            '{{%idx-category-parent_category_id}}',
            '{{%category}}'
        );

        $this->dropTable('{{%category}}');
    }
}
