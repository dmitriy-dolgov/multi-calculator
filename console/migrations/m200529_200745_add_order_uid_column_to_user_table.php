<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m200529_200745_add_order_uid_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'order_uid', $this->string(50)->unique()->comment('UID идентификатор формы заказа'));

        $this->createIndex(
            '{{%idx-user-order_uid}}',
            '{{%user}}',
            'order_uid'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-user-order_uid}}',
            '{{%user}}'
        );

        $this->dropColumn('{{%user}}', 'order_uid');
    }
}
