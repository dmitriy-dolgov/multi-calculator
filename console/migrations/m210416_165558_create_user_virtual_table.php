<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_virtual}}`.
 */
class m210416_165558_create_user_virtual_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_virtual}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_virtual}}');
    }
}
