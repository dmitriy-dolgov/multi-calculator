<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%texts}}`.
 */
class m210202_173623_create_texts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%texts}}', [
            'id' => $this->primaryKey(),
            'group' => $this->string(50)->comment('Группа, к которой относится текст'),
            'type' => $this->string(255)->comment('text, html и т. п.'),
            'data' => $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%texts}}');
    }
}
