<?php

namespace common\models\repositories;

use common\models\db\User;
use Yii;

class UserRepository extends \yii\db\ActiveRecord
{
    /**
     * Берет из базы данных значение параметра $name.
     * Если нет такого значения, то генерирует для него случайное название.
     *
     * @param string $name название значения
     * @param string $postfix для добааление в конец названия
     */
    protected static function handeParameterElement(string $name, string $postfix = '')
    {
        /*for (User::find()->exists([$name])) {

        }*/

        //if (User::findOne(['$name' => $newUsername])) {
    }

    public static function createFakeUser($data)
    {
        $username, $email, $latLong, $password_ha = 'temp123')

        if (!$username) {
            for (; ;) {
                $username = "{fake_{$dataPrefix}";
                // Значение, используется для генерации уникальных значений.
                if (User::findOne(['username' => $newUsername])) {
                    \Yii::warning("Username already exists: '{$newUsername}'");
                }
                if (User::findOne(['username' => $newUsername])) {
                    \Yii::warning("Username already exists: '{$newUsername}'");
                    continue;
                }
            }
        }

        if (User::findOne(['username' => $newUsername])) {
            \Yii::warning("Username already exists: '{$newUsername}'");
            continue;
        }

        $newUser = new User();
        //$newUser->password_hash = '$2y$10$EXA/SoXj2Oq1Wa.lLisrl.XfhHAkA9CuID/P/.aftis2dxYlAoTI.';
        $newUser->password_hash = Yii::$app->security->generatePasswordHash($password);
        $newUser->email = "fake_{$dataPrefix}@emal.com";
        $newUser->username = $username;
        $newUser->login_lat_long = $coords[$i];
        $newUser->registration_ip = '127.0.0.1';
        $newUser->confirmed_at = time();
        $newUser->created_at = time();
        $newUser->updated_at = time();
        $newUser->auth_tf_enabled = 0;
        $newUser->gdpr_deleted = 0;
        $newUser->gdpr_consent = 0;
        $newUser->order_uid = bin2hex(random_bytes(10));
        $newUser->flags = 0;
        $newUser->last_login_at = '1622487545';
        $newUser->last_login_ip = '127.0.0.1';

        if ($newUser->save()) {
            echo "User '{$newUsername}' created";
        } else {
            echo "Couldn't create user with name '{$newUsername}'";
            return false;
        }
    }
}
