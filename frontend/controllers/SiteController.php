<?php

namespace frontend\controllers;

use backend\sse\NewOrderHandlingBackend;
use common\models\db\Component;
use common\models\db\ComponentSet;
use common\models\db\User;
use common\models\shop_order\ShopOrderAcceptorders;
use frontend\models\ShopOrderForm;
use frontend\sse\CustomerWaitResponseOrderHandling;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
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

    public function actionCourierOfMerchantInfo()
    {
        //site/courier-of-merchant-info

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = ['status' => ['no_status']];

        return $data;
    }

    //TODO: в модель и сделать через форму (https://streletzcoder.ru/rabotaem-s-ajax-v-yii-2/)
    public function actionCurrentUserInfo()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = ['status' => 'no_status'];

        if (Yii::$app->user->isGuest) {
            return [
                'status' => 'no_logged',
                'status_message' => 'User is not logged.',
            ];
        }

        //$age = DateTimeHelper::getAgeByBirthday();

        if (Yii::$app->user->profile->username) {
            return [
                'status' => 'success',
                'username' => Yii::$app->user->profile->username,
                'username_photo' => 'No username photo',
            ];
        }

        $data = [
            //'status' => 'wrong',
            // Произвольный текст, может содержать описание ошибки и т.п.
            'status_message' => '',
            'name' => 'test', null,
            'age' => null,
            'delivery_time' => '14:30',
        ];

        return $data;
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
        //die('AI');
        //Yii::$app->cache->delete('acceptedOrderMerchantData');
        //Yii::$app->cache->delete('acceptedOrderCourierData');

        //TODO: раскомментить для типа отдельная-пиццерия
        /*if (!$uid) {
            if (Yii::$app->params['domain-customer'] == 'pizza-customer.local') {
                //$uid = '2_e42c5272';
                $uid = 'set_1';
            } else {
                $uid = '2_e72d17a3';
            }
        }

        if (!$user = User::findByUid($uid)) {
            throw new NotFoundHttpException();
        }*/

        /*        $sypexGeo = new \omnilight\sypexgeo\Sypexgeo([
                    'database' => '@root/geo/SxGeoCity.dat',
                ]);
                $city = $sypexGeo->getCityFull($_SERVER['REMOTE_ADDR']);
                $sypexGeo->getCity('185.174.210.231');*/
        //$city = Yii::$app->sypexGeo->getCity($_SERVER['REMOTE_ADDR']);

        CustomerWaitResponseOrderHandling::cleanUserData();

        $form = new ShopOrderForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $orderId = $this->shopOrderSignalService->create($form);
            return $this->redirect(['order-info', 'id' => $orderId]);
        }

        $components = [];

        //TODO: закомментить для типа отдельная-пиццерия
        $components = Component::findAll(['user_id' => null]);
        //TODO: раскомментить для типа отдельная-пиццерия
        /*if ($profile = $user->profile) {
            $components = $profile->user->getComponents()->forOrder()->all();
        }*/

        $componentSets = ComponentSet::find()->all();

        //$activeUsers = User::find()->activeAcceptOrders()->all();
        $activeUsers = User::find()->all();

        return $this->render('index', [
            'uid' => $uid,
            'activeUsers' => $activeUsers,
            'components' => $components,
            'componentSets' => $componentSets,
        ]);
    }

    public function actionOrderCreateAjax()
    {
        // site/order-create-ajax

        $response = ['status' => 'error'];

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $users = [];
        foreach (Yii::$app->request->post()['ShopOrderForm']['user_ids'] as $userId) {
            if (!$aUser = User::findOne($userId)) {
                //$response['msg'] = 'User ' . $userId . ' not found';
                //return $response;
                Yii::error(Yii::t('app', 'User {user} not found', ['user' => $userId]));
                continue;
            }
            $users[] = $aUser;
        }
        if (!$users) {
            $response['msg'] = Yii::t('app', 'Pizzerias not found');
            return $response;
        }

        $model = new ShopOrderForm();
        if ($model->load(Yii::$app->request->post()) && ($shopOrder = $model->save($users,
                Yii::$app->request->post()['ShopOrderForm']))
        ) {
            $response['status'] = 'success';
            $response['order_uid'] = $shopOrder->order_uid;

            //$shopOrder = ShopOrder::findOne($orderId);
            $orderData = ShopOrderAcceptorders::getAnOrder($shopOrder);
            $orderData['status'] = 'created';

            //$worker = CoWorker::findOne($shopOrder->user_id);
            //$shopOrderMaker = new ShopOrderMaker($workerUid);

            $orderHtml = $this->renderPartial('@backend/views/worker/_order_element', ['worker' => null, 'orderData' => $orderData]);
            NewOrderHandlingBackend::addNewOrder($orderHtml);

        } else {
            //$response['msg'] = 'Unknown server error';
            throw new InternalErrorException();
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
