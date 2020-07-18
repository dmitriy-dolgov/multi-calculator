<?php

namespace app\modules\vendor\controllers;

use app\models\ContactForm;
use app\models\db\ComponentSet;
use app\models\db\Profile;
use app\models\db\ShopOrder;
use app\models\db\ShopOrderStatus;
use app\models\db\User;
use app\modules\vendor\models\ShopOrderForm;
use app\modules\vendor\models\ShopOrderSignalService;
use app\modules\vendor\models\Vendor;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Default controller for the `vendor` module
 */
class DefaultController extends Controller
{
    protected $shopOrderSignalService;

    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'order-info'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['order-info'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }*/

    public function __construct($id, $module, ShopOrderSignalService $shopOrderSignalService, $config = [])
    {
        $this->shopOrderSignalService = $shopOrderSignalService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                //'view' => '@yiister/gentelella/views/error',
            ],
        ];
    }

    public function actionIndex($userUniqueId)
    {
        /*$form = new ShopOrderForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $orderId = $this->shopOrderSignalService->create($form);
            return $this->redirect(['order-info', 'id' => 'order-id']);
        }*/

        $components = [];

        if ($profile = (new Vendor())->getProfileForOrderPage($userUniqueId)) {
            $components = $profile->user->getComponents()->forOrder()->all();
        }

        return $this->render('index', [
            'uid' => $userUniqueId,
            'components' => $components,
            //'componentSets' => $componentSets,
        ]);
    }

    public function actionOrder($uid)
    {
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

        if ($profile = $user->profile) {
            $components = $profile->user->getComponents()->forOrder()->all();
        }

        $componentSets = ComponentSet::find()->all();

        $activeUsers = User::find()->activeAcceptOrders()->all();

        return $this->render('index', [
            'uid' => $uid,
            'activeUsers' => $activeUsers,
            'components' => $components,
            'componentSets' => $componentSets,
        ]);
    }

    public function actionOrderInfo($uid)
    {
        //die('Скоро здесь будет информация о входящих заказах !');

        //$shopOrders = Yii::$app->user->identity->shopOrders;

        if (!$shopOrder = ShopOrder::findOne(['order_uid' => $uid])) {
            throw new NotFoundHttpException();
        }

        $statusGlobal = 'created';
        $pizzeriasSentOffer = [];
        /*foreach ($shopOrder->shopOrderStatuses as $status) {
            if ($status == 'offer-sent-to-customer') {
                $statusGlobal = 'offer-sent-to-customer';

            }
        }*/

        foreach ($shopOrder->users as $pUser) {
            $statuses = ShopOrderStatus::find()->where([
                'shop_order_id' => $shopOrder->getPrimaryKey(),
                'user_id' => $pUser->getPrimaryKey(),
            ])->orderBy(['id' => SORT_ASC])->all();

            $lastStatusName = $statuses[count($statuses) - 1]->type;
            if ($lastStatusName == 'offer-sent-to-customer') {
                $statusGlobal = 'offer-sent-to-customer';
            } elseif ($lastStatusName == 'order-cancelled-by-user') {
                $statusGlobal = 'order-cancelled-by-user';
            }
        }

        return $this->render('order-info', [
            'order' => $shopOrder,
            'statusGlobal' => $statusGlobal,
        ]);
    }

    /*public function actionTest()
    {
        return $this->render('index', [
            'ifTest' => true,
        ]);
    }*/

    /*public function actionTestWindow()
    {
        $this->layout = '@app/views/layouts/clean-simple';

        return $this->render('test-frame');
    }*/

    /*public function actionOrder()
    {
        $this->layout = '@app/views/layouts/clean-simple';

        return $this->render('test-frame');
    }*/

    public function actionGetOrderForm($url, $test = false)
    {
        $this->layout = false;

        if ($profile = (new Vendor())->getProfileForOrderPage($url, $test)) {
            $components = $profile->user->getComponents()->forOrder()->all();

            return $this->renderPartial('_content', [
                'components' => $components,
            ]);
        }

        //TODO: реализовать полностью
        return Yii::t('app', 'Wrong domain of the parent page!');
    }

    public function actionOrderPanel($uid)
    {
        if (!$user = User::findByUid($uid)) {
            throw new NotFoundHttpException();
        }

        return $this->render('order-panel', [
            'uid' => $uid,
        ]);
    }

    /*public function actionOrderFormatting($uid)
    {
        if (!$user = User::findByUid($uid)) {
            throw new NotFoundHttpException();
        }

        return $this->render('order-formatting', [
            'uid' => $uid,
        ]);
    }*/

    public function actionOrderCreate()
    {
        /*if (!$user = User::findByUid(Yii::$app->request->post('user_uid'))) {
            throw new NotFoundHttpException();
        }*/
        /*if (!$user = User::findOne(Yii::$app->request->post()['ShopOrderForm']['user_id'])) {
            throw new NotFoundHttpException();
        }*/

        $users = [];
        foreach (Yii::$app->request->post()['ShopOrderForm']['user_ids'] as $userId) {
            if (!$aUser = User::findOne($userId)) {
                throw new NotFoundHttpException();
            }
            $users[] = $aUser;
        }
        if (!$users) {
            throw new NotFoundHttpException();
        }

        $model = new ShopOrderForm();
        if ($model->load(Yii::$app->request->post()) && ($shopOrder = $model->save($users,
                Yii::$app->request->post()['ShopOrderForm']))) {
            Yii::$app->session->setFlash('orderFormSubmitted');

            //return $this->redirect(['order-info']);

            //$shopOrders = Yii::$app->user->identity->shopOrders;
            //$shopOrders = ShopOrder::find()->orderBy(['id' => SORT_DESC])->one();

            /*return $this->render('order-info', [
                'shopOrders' => [$shopOrders],
            ]);*/
            return $this->redirect(['order-info', 'uid' => $shopOrder->order_uid]);
        }

        throw new ServerErrorHttpException();
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

    public function actionOrderCompose()
    {
        if (!$user = User::findByUid(Yii::$app->request->post('user_uid'))) {
            throw new NotFoundHttpException();
        }

        $model = new ShopOrderForm();

        //TODO: здесь явно что-то не так
        if ($model->load(Yii::$app->request->post()) && ($shopOrder = $model->save($user,
                Yii::$app->request->post()['ShopOrderForm']))) {
            Yii::$app->session->setFlash('orderFormSubmitted');
            return $this->redirect(['order-info', 'uid' => $shopOrder->order_uid]);
        }

        throw new ServerErrorHttpException();
    }

    public function actionPizzeriaInfo($id)
    {
        //TODO: сделать через uid
        if (!$pizzeriaModel = User::findOne($id)) {
            throw new NotFoundHttpException();
        }

        return $this->render('pizzeria-info', [
            'pizzeriaModel' => $pizzeriaModel,
        ]);
    }

    public function actionEmpty()
    {
        return '';
    }
}
