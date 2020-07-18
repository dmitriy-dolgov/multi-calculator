<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%component}}`.
 */
class m200420_042924_add_description_column_to_component_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%component}}', 'description', $this->text());
        $this->addColumn('{{%component}}', 'short_description', $this->text());
        $this->addColumn('{{%component}}', 'price', $this->decimal(8,2));
        $this->addColumn('{{%component}}', 'price_discount', $this->decimal(8,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%component}}', 'description');
        $this->dropColumn('{{%component}}', 'short_description');
        $this->dropColumn('{{%component}}', 'price');
        $this->dropColumn('{{%component}}', 'price_discount');
    }
}
