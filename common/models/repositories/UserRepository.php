<?php

namespace common\models\repositories;

use common\helpers\Database;
use common\models\db\Profile;
use common\models\db\User;
use Yii;

class UserRepository extends \yii\db\ActiveRecord
{
    const DEFAULT_USER_PASSWORD;

    /**
     * @param $companyLatLong string широта и долгота в таком шаблоне: '55.74958090241472;37.54247323613314'
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function createFakeUser(string $companyLatLong)
    {
        $transaction = static::getDb()->beginTransaction();

        try {
            //DEFAULT_USER_PASSWORD$password = 'temp123';
            $newUsername = Database::handeParameterElement(User::class, 'username', 'fake_name');
            $newEmail = Database::handeParameterElement(User::class, 'email', 'fake@emal.com');

            $newUser = new User();
            $newUser->username = $newUsername;
            $newUser->email = $newEmail;
            //$newUser->password_hash = '$2y$10$EXA/SoXj2Oq1Wa.lLisrl.XfhHAkA9CuID/P/.aftis2dxYlAoTI.';
            $newUser->password_hash = Yii::$app->security->generatePasswordHash(self::DEFAULT_USER_PASSWORD);
            //$newUser->login_lat_long = $coords[$i];
            $newUser->registration_ip = '127.0.0.1';
            $newUser->confirmed_at = time();
            $newUser->created_at = time();
            $newUser->updated_at = time();
            $newUser->auth_tf_enabled = 0;
            $newUser->gdpr_deleted = 0;
            $newUser->gdpr_consent = 0;
            //$newUser->order_uid = bin2hex(random_bytes(10));
            $newUser->order_uid = Database::handeParameterElement(User::class, 'order_uid', bin2hex(random_bytes(10)));
            $newUser->flags = 0;
            //$newUser->last_login_at = '1622487545';
            //$newUser->last_login_ip = '127.0.0.1';

            if ($newUser->save()) {
                Yii::info("User '{$newUsername}' created");

                /** @var $newUserProfile Profile */
                $newUserProfile = $newUser->profile;
                $newUserProfile->name = Yii::t('app', 'Не указано');
                $newUserProfile->timezone = 'Europe/Moscow';
                $newUserProfile->company_lat_long = $companyLatLong;
                if ($newUserProfile->save()) {
                    Yii::info("User '{$newUsername}' created");
                } else {
                    throw new \Exception("Couldn't create User's profile. Username: '{$newUsername}'");
                }
            } else {
                throw new \Exception("Couldn't create user with name '{$newUsername}'");
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            throw $e;
        } finally {
            $transaction->rollBack();
        }
    }
}
