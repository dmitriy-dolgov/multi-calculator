<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%component}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200503_092051_add_user_id_column_to_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%component}}', 'user_id', $this->integer()->after('id'));

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-component-user_id}}',
            '{{%component}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-component-user_id}}',
            '{{%component}}',
            'user_id',
            '{{%user}}',
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
            '{{%fk-component-user_id}}',
            '{{%component}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-component-user_id}}',
            '{{%component}}'
        );

        $this->dropColumn('{{%component}}', 'user_id');
    }
}
