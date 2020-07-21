<?php

use yii\db\Migration;

/**
 * Class m200505_034335_prepare_initial_admin_data
 */
class m200505_034335_prepare_initial_admin_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // create a role named "administrator"
        $administratorRole = $auth->createRole('administrator');
        $administratorRole->description = 'Administrator';
        $auth->add($administratorRole);

        // create permission for certain tasks
        $permission = $auth->createPermission('user-management');
        $permission->description = 'User Management';
        $auth->add($permission);

        // let administrators do user management
        $auth->addChild($administratorRole, $auth->getPermission('user-management'));

        // create setup user
        //$user = new \Da\User\Model\User([
        $user = new \common\models\db\User([
            'scenario' => 'create',
            'email' => "TwilightTowerDU@gmail.com",
            'username' => "daiviz",
            'password' => ")opPos4TrofQnv*z"  // >6 characters!
        ]);
        $user->confirmed_at = time();
        $user->save();

        // assign role to our setup-user
        $auth->assign($administratorRole, $user->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m200505_034335_prepare_initial_admin_data cannot be reverted.\n";

        $auth = Yii::$app->authManager;

        // delete permission
        $auth->remove($auth->getPermission('user-management'));

        // delete setup-user and administrator role
        $administratorRole = $auth->getRole("administrator");
        $user = \Da\User\Model\User::findOne(['username' => 'daiviz']);
        $auth->revoke($administratorRole, $user->id);
        $user->delete();

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200505_034335_prepare_initial_admin_data cannot be reverted.\n";

        return false;
    }
    */
}
