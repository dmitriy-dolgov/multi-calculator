<?php

namespace frontend\controllers;

use common\models\db\Component;
use common\models\db\ComponentSet;
use common\models\db\User;
use frontend\models\ShopOrderForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
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
            /*'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
        ];
    }

    public function actionSignalToParent($result)
    {
        if ($result == 'logged') {
            $s = <<<STR
<html>
<head></head>
<body>
<script>
function inIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}
if (inIframe () && parent && parent.gl.functions.setLogged) {
    parent.gl.functions.setLogged(); 
} else {
    //window.location.href = '/';
    document.write('You are logged in.');
}
</script>
</body>
</html>
STR;
            echo $s;

            \Yii::$app->end();
        }

        throw new NotFoundHttpException();
    }

    public function actionSignalToParentOpener($result)
    {
        if ($result == 'logged') {
            $s = <<<STR
<html>
<head></head>
<body>
<script>
function inIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}
if (inIframe () && parent && parent.gl.functions.setLogged) {
    parent.gl.functions.setLogged(); 
} else {
    if (window.opener && !window.opener.closed) {
        window.opener.location = '/site/signal-to-parent?result=logged';
        window.opener.focus();
        window.close();
    } else {
        window.location = '/';
    }
}
</script>
</body>
</html>
STR;
            echo $s;

            \Yii::$app->end();
        }

        throw new NotFoundHttpException();
    }

    public function actionIndex($uid = null)
    {
        if (!$uid) {
            if (Yii::$app->params['domain-customer'] == 'pizza-customer.local') {
                //$uid = '2_e42c5272';
                $uid = 'set_1';
            } else {
                $uid = '2_e72d17a3';
            }
        }

        if (!$user = User::findByUid($uid)) {
            throw new NotFoundHttpException();
        }

        /*        $sypexGeo = new \omnilight\sypexgeo\Sypexgeo([
                    'database' => '@root/geo/SxGeoCity.dat',
                ]);
                $city = $sypexGeo->getCityFull($_SERVER['REMOTE_ADDR']);
                $sypexGeo->getCity('185.174.210.231');*/
        //$city = Yii::$app->sypexGeo->getCity($_SERVER['REMOTE_ADDR']);

        $form = new ShopOrderForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $orderId = $this->shopOrderSignalService->create($form);
            return $this->redirect(['order-info', 'id' => $orderId]);
        }

        $components = [];

        $components = Component::findAll(['user_id' => null]);

        if ($profile = $user->profile) {
            $components = $profile->user->getComponents()->forOrder()->all();
        }

        //$componentSets = ComponentSet::find()->all();

        $activeUsers = User::find()->activeAcceptOrders()->all();

        return $this->render('index', [
            'uid' => $uid,
            'activeUsers' => $activeUsers,
            'components' => $components,
            'componentSets' => $componentSets,
        ]);
    }

    public function actionOrderCreateAjax()
    {
        $response = ['status' => 'error'];

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $users = [];
        foreach (Yii::$app->request->post()['ShopOrderForm']['user_ids'] as $userId) {
            if (!$aUser = User::findOne($userId)) {
                $response['msg'] = 'User ' . $userId . ' not found';
                return $response;
            }
            $users[] = $aUser;
        }
        if (!$users) {
            $response['msg'] = 'Users not found';
            return $response;
        }

        $model = new ShopOrderForm();
        if ($model->load(Yii::$app->request->post()) && ($shopOrder = $model->save($users,
                Yii::$app->request->post()['ShopOrderForm']))
        ) {
            $response['status'] = 'success';
            $response['order_uid'] = $shopOrder->order_uid;
        } else {
            $response['msg'] = 'Unknown server error';
        }

        return $response;
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    /*public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success',
                    'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Displays about page.
     *
     * @return mixed
     */
    /*public function actionAbout()
    {
        return $this->render('about');
    }*/
}
