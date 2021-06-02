<?php

namespace common\models\repositories;

use common\helpers\Database;
use common\models\db\User;
use Yii;

class UserRepository extends \yii\db\ActiveRecord
{
    public static function createFakeUser()
    {
        //$username, $email, $latLong, $password_ha = 'temp123')

        $transaction = static::getDb()->beginTransaction();

        try {
            $password = 'temp123';
            $newUsername = Database::handeParameterElement(User::class, 'username', 'fake_name');
            $newEmail = Database::handeParameterElement(User::class, 'email', 'fake@emal.com');

            $newUser = new User();
            $newUser->username = $newUsername;
            $newUser->email = $newEmail;
            //$newUser->password_hash = '$2y$10$EXA/SoXj2Oq1Wa.lLisrl.XfhHAkA9CuID/P/.aftis2dxYlAoTI.';
            $newUser->password_hash = Yii::$app->security->generatePasswordHash($password);
            //$newUser->login_lat_long = $coords[$i];
            $newUser->registration_ip = '127.0.0.1';
            $newUser->confirmed_at = time();
            $newUser->created_at = time();
            $newUser->updated_at = time();
            $newUser->auth_tf_enabled = 0;
            $newUser->gdpr_deleted = 0;
            $newUser->gdpr_consent = 0;
            $newUser->order_uid = bin2hex(random_bytes(10));
            $newUser->flags = 0;
            //$newUser->last_login_at = '1622487545';
            $newUser->last_login_ip = '127.0.0.1';

            if ($newUser->save()) {
                Yii::info("User '{$newUsername}' created");
            } else {
                throw new \Exception("Couldn't create user with name '{$newUsername}'");
            }

            $transaction->commit();
        } finally {
            $transaction->rollBack();
        }
    }
}
