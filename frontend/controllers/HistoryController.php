<?php

namespace frontend\controllers;

use common\models\db\HistoryProfile;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class HistoryController extends Controller
{
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

    public function actionProfileSave()
    {
        //history/profile-save

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $hs = new HistoryProfile();
        $hs->name = 'test';
        $hs->save();

        return true;
    }
}
