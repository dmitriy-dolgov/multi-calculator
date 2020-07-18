<?php

use yii\db\Migration;

/**
 * Class m200619_085058_fill_unit_table
 */
class m200619_085058_fill_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%unit}}', [
            'name' => 'Amount (units)',
            'short_name' => 'Amount (pcs)',
            'symbol' => 'pcs',
            'symbol_pattern' => '{value} pcs',
        ]);

        $this->insert('{{%unit}}', [
            'name' => 'Weight (kilogram)',
            'short_name' => 'Weight (kg)',
            'symbol' => 'kg',
            'symbol_pattern' => '{value} kg',
        ]);

        $this->insert('{{%unit}}', [
            'name' => 'Weight (gram)',
            'short_name' => 'Weight (gm)',
            'symbol' => 'gm',
            'symbol_pattern' => '{value} gm',
        ]);

        $this->insert('{{%unit}}', [
            'name' => 'Volume (milliliter)',
            'short_name' => 'Volume (ml.)',
            'symbol' => 'ml.',
            'symbol_pattern' => '{value} ml.',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200619_085058_fill_unit_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200619_085058_fill_unit_table cannot be reverted.\n";

        return false;
    }
    */
}
