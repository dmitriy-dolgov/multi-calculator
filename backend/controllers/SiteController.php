<?php

namespace backend\controllers;

use common\models\ContactForm;
use common\models\db\Component;
use common\models\LoginForm;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $components = Yii::$app->user->isGuest ? [] : $components = Yii::$app->user->identity->components;

        return $this->render('index', [
            'components' => $components,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $this->layout = '@backend/views/layouts/registration';

        $model = new ContactForm();

        if (!Yii::$app->user->isGuest) {
            $userName = Yii::$app->user->identity->profile->name;
            $model->name = $userName ?: Yii::$app->user->identity->username;
            $model->email = Yii::$app->user->identity->email;
        }

        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $this->layout = '@backend/views/layouts/registration';

        return $this->render('about');
    }
}
