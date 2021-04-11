<?php

namespace frontend\controllers;

use yii\web\Response;
use common\models\db\HistoryProfile;
use common\services\HistoryProfileService;
use DeepCopy\Exception\PropertyException;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Site controller
 */
class HistoryController extends Controller
{
    const POST_INFO_NAME = 'incoming_info';


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Сохранения профиля со стороны пользователя.
     *
     * @return bool
     */

    public function actionProfileSave()
    {
        //history/profile-save

        Yii::$app->response->format = Response::FORMAT_JSON;

        //TODO: в репозитоии это делать
        if ($remoteInfo = Yii::$app->request->post(self::POST_INFO_NAME)) {
            //TODO: сделать нормально через форму
            $hs = new HistoryProfile();
            $hs->name = HistoryProfileService::instance()->generateRandomNameForCustomer();
            $hs->remote_info = Json::encode($remoteInfo);
            //$hs->server_info = '';
            //TODO: в поведение
            $hs->created_at = date('Y-m-d H:i:s');

            return $hs->save();
        }

        throw new PropertyException('Required POST variable is not set: `' . self::POST_INFO_NAME . '`');
    }
}
