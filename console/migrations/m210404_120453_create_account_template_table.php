<?php

use yii\db\Migration;

/**
 * Последовательность действий при замене пользовательской базы.
 *
 * 1) Создается ноовый экземпляр {{%account_template}}.
 * 2) Cтарый пользователь остается, берется его ID и вставляется в `old_user_id`.
 * 3) Заполняются `name` и `description`.
 * 4) Через `old_user_id` линкуется с таблицей `user`.
 * 5) Создается новый пользователь, в него копируются данные старого пользвателя.
 * 6) Берется ID нового пользователя и вставляется в `new_user_id`.
 */


/**
 * Handles the creation of table `{{%account_template}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m210404_120453_create_account_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%account_template}}', [
            'id' => $this->primaryKey(),
            'old_user_id' => $this->integer()->comment('Ссылка на сохраненный в архиве аккаунт.'),
            'new_user_id' => $this->integer()->comment('Ссылка новый созданный аккаунт.'),
            'name' => $this->string(255),
            'description' => $this->text(),
        ]);

        // creates index for column `old_user_id`
        $this->createIndex(
            '{{%idx-account_template-old_user_id}}',
            '{{%account_template}}',
            'old_user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-account_template-old_user_id}}',
            '{{%account_template}}',
            'old_user_id',
            '{{%user}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `new_user_id`
        $this->createIndex(
            '{{%idx-account_template-new_user_id}}',
            '{{%account_template}}',
            'new_user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-account_template-new_user_id}}',
            '{{%account_template}}',
            'new_user_id',
            '{{%user}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-account_template-old_user_id}}',
            '{{%account_template}}'
        );

        // drops index for column `old_user_id`
        $this->dropIndex(
            '{{%idx-account_template-old_user_id}}',
            '{{%account_template}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-account_template-new_user_id}}',
            '{{%account_template}}'
        );

        // drops index for column `new_user_id`
        $this->dropIndex(
            '{{%idx-account_template-new_user_id}}',
            '{{%account_template}}'
        );

        $this->dropTable('{{%account_template}}');
    }
}
