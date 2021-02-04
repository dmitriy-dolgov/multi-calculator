<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%texts}}`.
 *
 * Таблица для хранения текстовых данных.
 */
class m210202_173623_create_texts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%texts}}', [
            'id' => $this->string(100)->notNull()->unique(),
            'group' => $this->string(50)->comment('Группа, к которой относится текст (договор, страница и т.п.)'),
            'type' => $this->string(255)->comment('text, html, mime-type и т.п.'),
            'content' => $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext'),
        ]);

        $this->createIndex(
            '{{%idx-texts-id}}',
            '{{%texts}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-texts-id}}',
            '{{%texts}}'
        );

        $this->dropTable('{{%texts}}');
    }
}
