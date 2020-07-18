<?php

use yii\db\Migration;

/**
 * Class m200619_145303_fill_component_switch_group_table
 */
class m200619_145303_fill_component_switch_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%component_switch_group}}', [
            'name' => 'Size of pizza',
            'description' => 'Size of pizza base.',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200619_145303_fill_component_switch_group_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200619_145303_fill_component_switch_group_table cannot be reverted.\n";

        return false;
    }
    */
}
