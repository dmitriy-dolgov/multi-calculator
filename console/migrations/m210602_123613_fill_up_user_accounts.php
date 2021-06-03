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
        $coords = [
            '55.74958090241472;37.54247323613314',
            '55.78863136588515;37.44675988832758',
            '55.84568433042044;37.729379871438084',
            '55.75095122759028;37.68105308990117',
            '55.72115988400856;37.48774590609692',
            '55.891548932356905;37.67008469607761',
            '55.76078841987557;37.59588219204036',
            '55.836437940564416l37.434382624429865',
            '55.761197731327755;37.523134639062654',
            '55.80169830229568;37.76320156388906',
        ];
        $coordsCount = count($coords);

        for ($i = 0; $i < $coordsCount; ++$i) {
            UserRepository::createFakeUser($coords[$i]);
        }
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
