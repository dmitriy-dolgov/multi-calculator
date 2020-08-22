<?php

namespace frontend\controllers;

use common\models\db\CoWorker;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderSearch;
use common\models\db\ShopOrderStatus;
use common\models\db\User;
use frontend\sse\MerchantOrderAccept;
use frontend\sse\OrderHandling;
use Sse\Data;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ShopOrderController implements the CRUD actions for ShopOrder model.
 */
class ShopOrderController extends Controller
{
    const TIME_LIMIT_FOR_LONGPOLL = 600;

    /*public function actionStop()
    {
        Yii::$app->cache->set('c-stop', true);
    }*/

    /**
     * Ожидание ответа одной из пиццерий - старт SSE.
     * Вызывается заказчиком пиццы.
     */
    public function actionWaitOrderCommand()
    {
        $sessId = Yii::$app->session->getId();

        //$sse = Yii::$app->sse;
        //$sse->addEventListener('merchant-order-accept', new MerchantOrderAccept($sessId));
        //$sse->start();

        $orderCommand = Yii::$app->cache->get('order-command');
        if (isset($orderCommand[$sessId])) {
            return 'process_already_exists';
        }

        $orderCommand[$sessId] = [];

        Yii::$app->cache->set('order-command', $orderCommand);

        (new OrderHandling())->waitForOrderCommand($sessId);
    }

    /**
     * Старт прослушки конкретного заказа.
     * Вызывается заказчиком пиццы.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionStartOrderAccept($orderUid)
    {
        //pizza-customer.local/shop-order/start-order-accept?orderUid=Glj4y20fg1io8hJ

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sessId = Yii::$app->session->getId();

        $orderCommand = Yii::$app->cache->get('order-command');

        if (!isset($orderCommand[$sessId])) {
            $orderCommand[$sessId] = [];
        }
        if (!isset($orderCommand[$sessId][$orderUid])) {
            $orderCommand[$sessId][$orderUid] = [];
        }
        if (!isset($orderCommand[$sessId][$orderUid]['info'])) {
            $orderCommand[$sessId][$orderUid]['info'] = [];
        }

        Yii::$app->cache->set('order-command', $orderCommand);

        return ['status' => 'success'];

        /*Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!$orderUid = Yii::$app->request->post('orderUid')) {
            throw new NotFoundHttpException();
        }

        $sessionId = Yii::$app->session->getId();
        $sessionOrderIdsKey = ['order-accept', 'order-uids', 'session-id' => $sessionId];

        if (!$data = Yii::$app->cache->get($sessionOrderIdsKey)) {
            $data = [];
        }

        $data[] = $orderUid;

        if (!Yii::$app->cache->set($sessionOrderIdsKey, $data)) {
            return ['status' => 'error'];
        }

        //TODO: на самом деле могут быть ошибки - учесть это
        return ['status' => 'success'];*/
    }

    /**
     * Обработать команду по статусу заказа.
     * Вызывается пиццерией.
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionOrderCommand()
    {
        //pizza-customer.local/shop-order/order-command?type=accepted-by-merchant&merchantId=2&orderUid=
        //pizza-customer.local/shop-order/order-command?type=accepted-by-courier&merchantId=2&courierId=2&orderUid=

        $type = Yii::$app->request->post('type', Yii::$app->request->get('type'));
        $orderUid = Yii::$app->request->post('orderUid', Yii::$app->request->get('orderUid'));
        $merchantId = Yii::$app->request->post('merchantId', Yii::$app->request->get('merchantId'));

        /*if (!ShopOrder::findOne($orderUid)) {
            Yii::error('Order not found. Order uid: ' . $orderUid);
            throw new NotFoundHttpException('Order not found!');
        }*/
        if (!$user = User::findOne($merchantId)) {
            Yii::error('User not found. User id: ' . $merchantId);
            throw new NotFoundHttpException('User not found!');
        }

        /*$orderInfo = [
            'sessionId' => Yii::$app->session->getId(),
            'time' => time(),
        ];*/

        //$sessId = Yii::$app->session->getId();

        /*$orderCommand = Yii::$app->cache->get('order-command');
        if (empty($orderCommand[$sessId])) {
            $orderCommand[$sessId] = [];
        }

        if (empty($orderCommand[$sessId][$orderUid])) {
            $orderCommand[$sessId][$orderUid];
        }

        if (empty($orderCommand[$sessId][$orderUid]['info'])) {
            $orderCommand[$sessId][$orderUid]['info'] = [];
        }*/

        $currentCustomerId = '';

        if (empty($orderCommand = Yii::$app->cache->get('order-command'))) {
            $orderCommand = [];
        }

        foreach ($orderCommand as $customerId => $orders) {
            if (isset($orders[$orderUid])) {
                $currentCustomerId = $customerId;
                break;
            } else {
                return 'no_order_in_command';
            }
        }

        switch ($type) {
            case 'accepted-by-merchant':
            {
                /*$orderInfo['acceptedOrderData'] = [
                    'order_status' => 'accepted-by-merchant',
                    'orderUid' => $orderUid,
                    'merchantData' => [
                        'name' => $user->profile->name,
                        'address' => $user->profile->location,
                        'company_lat_long' => $user->profile->company_lat_long,
                    ],
                ];*/

                if (isset($orderCommand[$currentCustomerId][$orderUid]['info']['order_status'])
                    && $orderCommand[$currentCustomerId][$orderUid]['info']['order_status'] == 'accepted-by-merchant'
                ) {
                    // Эта данные уже установлены
                    //TODO: возможно следует отдельно обработать
                    break;
                }

                $orderCommand[$currentCustomerId][$orderUid]['info'] = [
                    'order_status' => 'accepted-by-merchant',
                    'orderUid' => $orderUid,
                    'merchantData' => [
                        'name' => $user->profile->name,
                        'address' => $user->profile->location,
                        'company_lat_long' => $user->profile->company_lat_long,
                    ],
                ];

                Yii::$app->cache->set('order-command', $orderCommand);
                break;
            }
            case 'accepted-by-courier':
            {
                $courierId = Yii::$app->request->post('courierId', Yii::$app->request->get('courierId'));
                if (!$courier = CoWorker::findOne($courierId)) {
                    Yii::error('Co-worker not found. Co-worker id: ' . $courierId);
                    throw new NotFoundHttpException('Co-worker not found!');
                }

                $orderInfo['acceptedOrderData'] = [
                    'order_status' => 'accepted-by-merchant',
                    'orderUid' => $orderUid,
                    'merchantData' => [
                        'name' => $user->profile->name,
                        'address' => $user->profile->location,
                        'company_lat_long' => $user->profile->company_lat_long,
                    ],
                    'courierData' => [
                        'name' => $courier->name,
                    ],
                ];

                Yii::$app->cache->set('order-info', json_encode($orderInfo));
                break;
            }
            default:
            {
                Yii::error('Wrong type: ' . $type);
                throw new NotFoundHttpException('Wrong type.');
            }
        }

        return 'success';
    }


    /*public function actionWaitOrder()
    {
        set_time_limit(self::TIME_LIMIT_FOR_LONGPOLL);

        $result = ['status' => 'error'];

        file_put_contents('naem', 'just a content');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $orderUid = Yii::$app->request->post('orderUid');
        //Yii::debug('actionWaitOrder() got $orderUid: ' . $orderUid, 'order-accept');
        $cacheKey = ['order_handling', 'accepted_by_merchant', 'data', 'orderUid' => $orderUid];

        for (; ;) {
//            if (Yii::$app->cache->get('c-stop')) {
//                Yii::$app->cache->delete('c-stop');
//                break;
//            }
            if ($storedData = Yii::$app->cache->get($cacheKey)) {
                //Yii::debug('actionWaitOrder() in cycle', 'order-accept');
                $result = ['status' => 'success', 'data' => $storedData];
                Yii::$app->cache->delete($cacheKey);
                break;
            }

            sleep(3);
        }

        return $result;
    }

    public function actionWaitCourier()
    {
        set_time_limit(self::TIME_LIMIT_FOR_LONGPOLL);

        $result = ['status' => 'error'];

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $orderUid = Yii::$app->request->post('orderUid');
        Yii::debug('actionWaitCourier() got $orderUid: ' . $orderUid, 'order-accept');
        $cacheKey = ['order_handling', 'accepted_by_courier', 'data', 'orderUid' => $orderUid];

        for (; ;) {
            if ($storedData = Yii::$app->cache->get($cacheKey)) {
                Yii::debug('actionWaitCourier() in cycle', 'order-accept');
                $result = ['status' => 'success', 'data' => $storedData];
                Yii::$app->cache->delete($cacheKey);
                break;
            }

            sleep(3);
        }

        return $result;
    }*/

    public function actionAcceptOrderByMerchant($orderUid, $merchantId)
    {
        //pizza-customer.local/shop-order/accept-order-by-merchant

        if (!$user = User::findOne($merchantId)) {
            Yii::error('User not found. User id: ' . $merchantId);
            throw new NotFoundHttpException('User not found!');
        }

        $acceptedOrderData = [
            'order_status' => 'accepted-by-merchant',
            'orderUid' => $orderUid,
            'merchantData' => [
                'name' => $user->profile->name,
                'address' => $user->profile->location,
                'company_lat_long' => $user->profile->company_lat_long,
            ],
        ];

        //Yii::debug('actionAcceptOrderByMerchant() got $orderUid: ' . $orderUid, 'order-accept');
        Yii::$app->cache->set(['order_handling', 'accepted_by_merchant', 'data', 'orderUid' => $orderUid],
            $acceptedOrderData);

        return 'success';
    }

    public function actionAcceptOrderByCourier($orderUid, $merchantId, $courierId)
    {
        //pizza-customer.local/shop-order/accept-order-by-courier

        if (!$user = User::findOne($merchantId)) {
            Yii::error('User not found. User id: ' . $merchantId);
            throw new NotFoundHttpException('User not found!');
        }

        if (!$courier = CoWorker::findOne($courierId)) {
            Yii::error('Co-worker not found. Co-worker id: ' . $courierId);
            throw new NotFoundHttpException('Co-worker not found!');
        }

        $acceptedOrderData = [
            'order_status' => 'accepted-by-courier',
            'orderUid' => '$orderUid',
            'merchantData' => [
                'name' => $user->profile->name,
                'address' => $user->profile->location,
                'company_lat_long' => $user->profile->company_lat_long,
            ],
            'courierData' => [
                'name' => $courier->name,
            ],
        ];

        Yii::$app->cache->set(['order_handling', 'accepted_by_courier', 'data', 'orderUid' => $orderUid],
            $acceptedOrderData);

        return 'success';
    }

    /**
     * Lists all ShopOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopOrderSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->user->identity->getShopOrders0(),
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopOrder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ShopOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionOrderStatus($orderUid)
    {
        $result = ['status' => 'error'];

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($shopOrder = ShopOrder::findOne(['order_uid' => $orderUid])) {
            $result['status'] = 'success';
            //$result['data']['order-status'] = 'offer-sent-to-customer';
            $result['data']['order-status'] = 'new';
        }

        return $result;
    }

    public function actionStatusChange()
    {
        $shopOrderModelId = Yii::$app->request->post()['shopOrderModelId'];
        $newStatusName = Yii::$app->request->post()['newStatusName'];

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$shopOrderModel = ShopOrder::findOne($shopOrderModelId)) {
            return json_encode([
                'success' => false,
            ]);
        }

        //foreach ($shopOrderModel->users as $user) {
        $newShopOrderStatus = new ShopOrderStatus();
        $newShopOrderStatus->user_id = Yii::$app->user->id;
        $newShopOrderStatus->type = $newStatusName;
        $newShopOrderStatus->accepted_at = date('Y-m-d H:i:s');

        //TODO: проверить почему вылазит false даже если связь создается
        $newShopOrderStatus->link('shopOrder', $shopOrderModel);
        /*if (!$newShopOrderStatus->link('shopOrder', $shopOrderModel)) {
            return json_encode([
                'success' => false,
            ]);
        }*/
        //}

        return json_encode([
            'success' => true,
        ]);
    }

    /**
     * Updates an existing ShopOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }*/

    /**
     * TODO: реализовать через deleted_at
     * Deletes an existing ShopOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the ShopOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
