<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%history_profile}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user_customer}}`
 */
class m210410_172148_create_history_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history_profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('Инициатор типа продавец'),
            'user_customer_id' => $this->integer()->comment('Инициатор типа покупатель'),
            'name' => $this->string(255)->comment('Короткое название'),
            'about' => $this->text()->comment('Информация для чего создавался проект'),
            'server_info' => $this
                ->getDb()
                ->getSchema()
                ->createColumnSchemaBuilder('mediumtext')
                ->comment('Данные на стороне сервера в произвольном формате json'),
            'remote_info' => $this
                ->getDb()
                ->getSchema()
                ->createColumnSchemaBuilder('mediumtext')
                ->comment('Данные на стороне пользователя в произвольном формате json'),
            'created_at' => $this->datetime(),
            'changed_at' => $this->datetime(),
            'deleted_at' => $this->datetime(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-history_profile-user_id}}',
            '{{%history_profile}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-history_profile-user_id}}',
            '{{%history_profile}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_customer_id`
        $this->createIndex(
            '{{%idx-history_profile-user_customer_id}}',
            '{{%history_profile}}',
            'user_customer_id'
        );

        // add foreign key for table `{{%user_customer}}`
        $this->addForeignKey(
            '{{%fk-history_profile-user_customer_id}}',
            '{{%history_profile}}',
            'user_customer_id',
            '{{%user_customer}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-history_profile-user_id}}',
            '{{%history_profile}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-history_profile-user_id}}',
            '{{%history_profile}}'
        );

        // drops foreign key for table `{{%user_customer}}`
        $this->dropForeignKey(
            '{{%fk-history_profile-user_customer_id}}',
            '{{%history_profile}}'
        );

        // drops index for column `user_customer_id`
        $this->dropIndex(
            '{{%idx-history_profile-user_customer_id}}',
            '{{%history_profile}}'
        );

        $this->dropTable('{{%history_profile}}');
    }
}
