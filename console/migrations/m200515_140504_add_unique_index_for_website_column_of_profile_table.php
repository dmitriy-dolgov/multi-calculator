<?php

use yii\db\Migration;

/**
 * Class m200515_140504_add_unique_index_for_website_column_of_profile_table
 */
class m200515_140504_add_unique_index_for_website_column_of_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('uidx-profile-website', 'profile', 'website', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m200515_140504_add_unique_index_for_website_column_of_profile_table cannot be reverted.\n";

        $this->dropIndex('uidx-profile-website', 'profile');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200515_140504_add_unique_index_for_website_column_of_profile_table cannot be reverted.\n";

        return false;
    }
    */
}
