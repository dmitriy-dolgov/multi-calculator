<?php

use Da\User\Helper\MigrationHelper;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_order_signal}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200518_091507_create_shop_order_signal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop_order_signal}}', [
            //'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'emails' => $this->text()->comment('JSON - список email куда сигналить о новом заказе'),
            'phones' => $this->text()->comment('JSON - список телефонов куда отправлять SMS о новом заказе'),
        ]);

        $this->addPrimaryKey('{{%shop_order_signal_pk}}', '{{%shop_order_signal}}', 'user_id');

        // creates index for column `user_id`
        /*$this->createIndex(
            '{{%idx-shop_order_signal-user_id}}',
            '{{%shop_order_signal}}',
            'user_id'
        );*/

        $restrict = MigrationHelper::isMicrosoftSQLServer($this->db->driverName) ? 'NO ACTION' : 'RESTRICT';

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-shop_order_signal-user_id}}',
            '{{%shop_order_signal}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            $restrict
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-shop_order_signal-user_id}}',
            '{{%shop_order_signal}}'
        );

        // drops index for column `user_id`
        /*$this->dropIndex(
            '{{%idx-shop_order_signal-user_id}}',
            '{{%shop_order_signal}}'
        );*/

        $this->dropTable('{{%shop_order_signal}}');
    }
}
