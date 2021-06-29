<?php

namespace common\models\user;

use Yii;
use yii\base\Model;


class UserRepository extends Model
{
    /**
     * TODO: в модель и сделать через форму (https://streletzcoder.ru/rabotaem-s-ajax-v-yii-2/)
     *
     * @param bool $notLoggedUsersToo возвращать ли инфу для незалогированных пользователей
     * (если имеется такая в других источниках, например в сессии).
     *
     * @return array
     */
    public function getActualUserInfo($notLoggedUsersToo = true)
    {
        $userInfo = ['status' => 'not_status'];

        if ($notLoggedUsersToo) {
            if (Yii::$app->user->isGuest) {
                //TODO: иформация для НЕ залогиненых пользователей
                $userInfo['status_message'] = 'Guest test info';
            } else {
                $userInfo['name'] = Yii::$app->user->name;
                if (Yii::$app->user->profile->username) {
                    $userInfo['username'] = Yii::$app->user->profile->username ?? false;
                }
            }

            $userInfo['status'] = 'success';
        } else {
            if (Yii::$app->user->isGuest) {
                $userInfo['status'] = 'not_logged';
                $userInfo['status_message'] = Yii::t('app', 'User must be logged in.');
            } else {
                $userInfo['status'] = 'success';
                $userInfo['status_message'] = Yii::t('app', 'User is not logged.');
                $userInfo['username'] = Yii::$app->user->username ?? Yii::$app->user->name;
            }
        }

        return $userInfo;
    }
}
