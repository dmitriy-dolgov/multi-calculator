<?php

use yii\db\Migration;

/**
 * Class m200619_164831_fill_extend_unit_table
 */
class m200619_164831_fill_extend_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%unit}}', [
            'name' => 'Size (centimeter)',
            'short_name' => 'Size (cm)',
            'symbol' => 'cm',
            'symbol_pattern' => '{value} cm',
        ]);

        $this->insert('{{%unit}}', [
            'name' => 'Size (millimeter)',
            'short_name' => 'Size (mm.)',
            'symbol' => 'mm.',
            'symbol_pattern' => '{value} mm.',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200619_164831_fill_extend_unit_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200619_164831_fill_extend_unit_table cannot be reverted.\n";

        return false;
    }
    */
}
