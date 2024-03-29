<?php

namespace common\models\repositories;

use common\helpers\Database;
use common\models\db\Profile;
use common\models\db\User;
use Yii;
use yii\base\Component;

class UserRepository extends Component
{
    const DEFAULT_USER_PASSWORD = 'temp123';


    public static function createFakeUsers()
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
            self::createAFakeUser($coords[$i]);
        }
    }

    /**
     * @param string $companyLatLong string широта и долгота
     *      в таком шаблоне: '55.74958090241472;37.54247323613314'
     *      TODO: можно сделать проверку на правильный шаблок
     * @throws \Throwable
     */
    public static function createAFakeUser(string $companyLatLong)
    {
        //TODO: ракомментировать транзакцию
        //$transaction = \search::getDb()->beginTransaction();

        try {
            //DEFAULT_USER_PASSWORD$
            //password = 'temp123';
            $newUsername = Database::handeParameterElement(User::class, 'username', 'fake_name');
            $newEmail = Database::handeParameterElement(User::class, 'email', 'fake@email.com');

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
                if (!$newUser->profile) {
                    /** @var $profElem Profile */
                    $profElem = $newUser->getProfile()->one();
                    /** @var $rex Profile */
                    $profElem->name = Yii::t('app', 'Нет имени');
                    $profElem->timezone = 'Europe/Moscow';   //TODO при выхода за пределы Москвы - решить
                    $profElem->company_lat_long = $companyLatLong;

                    if ($profElem->save()) {
                        Yii::info("Profile saved for user '{$newUsername}' created");
                    } else {
                        throw new \Exception("Couldn't save User's profile. Username: '{$newUsername}'");
                    }
                } else {
                    throw new \Exception("Couldn't create user with name '{$newUsername}'");
                }
            }

            //$transaction->commit();
        } catch (\Exception $e) {
            throw $e;
        } catch
        (\Throwable $e) {
            throw $e;
        } finally {
            //$transaction->rollBack();
        }
    }
}
