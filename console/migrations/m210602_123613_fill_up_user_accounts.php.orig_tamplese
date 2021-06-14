<?php

use common\models\repositories\UserRepository;
use yii\db\Migration;

/**
 * Class m210602_123613_fill_up_user_accounts
 */
class m210602_123613_fill_up_user_accounts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        UserRepository::createFakeUsers();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210602_123613_fill_up_user_accounts cannot be reverted.\n";

        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210602_123613_fill_up_user_accounts cannot be reverted.\n";

        return false;
    }
    */
}
