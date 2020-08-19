<?php

namespace frontend\controllers;

use common\models\db\ShopOrder;
use common\models\db\ShopOrderSearch;
use common\models\db\ShopOrderStatus;
use common\models\db\User;
use izumi\longpoll\Server;
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
    //TODO: временный конструкт
    protected $currentOrderUid;

    public function actions()
    {
        return [
            'wait-order' => [
                'class' => 'izumi\longpoll\LongPollAction',
                'events' => ['order-accepted-by-merchant'],
                'callback' => [$this, 'orderAcceptedByMerchant'],
            ],
            'wait-courier' => [
                'class' => 'izumi\longpoll\LongPollAction',
                'events' => ['order-accepted-by-courier'],
                'callback' => [$this, 'orderAcceptedByCourier'],
            ],
        ];
    }

    public function orderAcceptedByMerchant(Server $server)
    {
        $server->responseData = false;

        if ($t = Yii::$app->request->get('t')) {
            // Первый запрос
            $orderUid = Yii::$app->request->get('orderUid');
            if ($orderUid) {
                //TODO: это может оказаться полезным в случае переоткрытия заказа - рассмотреть такие случаи
                Yii::$app->cache->delete(['order_handling', 'accepted_by_merchant', 'data', 'orderUid' => $orderUid]);
                Yii::$app->session->set('currentOrderUid', $orderUid);
                //$this->currentOrderUid = $orderUid;
            } else {
                Yii::error('No order ID on orderAcceptedByMerchant()');
            }
        } else {
            if ($orderUid = Yii::$app->session->get('currentOrderUid')) {
            //if ($orderUid = $this->currentOrderUid) {
                if ($storedData = Yii::$app->cache->get([
                    'order_handling',
                    'accepted_by_merchant',
                    'data',
                    'orderUid' => $orderUid,
                ])) {
                    $server->responseData = $storedData;
                    return;
                }
            }

            //TODO: проверить прекращается ли работа, не отсылается ли ответ клиенту
            exit;
        }
    }

    public function orderAcceptedByCourier(Server $server)
    {
        $server->responseData = Yii::$app->cache->get('acceptedOrderCourierData');
        //Yii::$app->cache->delete('acceptedOrderCourierData');
    }

    public function actionAcceptOrderByMerchant($orderUid, $merchantId)
    {
        //pizza-customer.local/shop-order/accept-order-by-merchant?merchantDataName=Дима пицца
        //pizza-customer.local/shop-order/accept-order-by-merchant?orderUid=6qNy1wt4VcRChNw&merchantId=2

        //$orderUid = Yii::$app->request->post('orderUid');
        //$merchantId = Yii::$app->request->post('merchantId');

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

        Yii::$app->cache->set(['order_handling', 'accepted_by_merchant', 'data', 'orderUid' => $orderUid],
            $acceptedOrderData);
        \izumi\longpoll\Event::triggerByKey('order-accepted-by-merchant');

        return 'success';
    }

    public function actionAcceptOrderByCourier($courierName = 'Дима курьер')
    {
        //pizza-customer.local/shop-order/accept-order-by-courier?courierName=Дима курьер

        $acceptedOrderData = [
            'order_status' => 'accepted-by-courier',
            'orderUid' => 'oId567f4',
            'courierData' => [
                'name' => $courierName,
            ],
        ];
        Yii::$app->cache->set('acceptedOrderCourierData', $acceptedOrderData);
        \izumi\longpoll\Event::triggerByKey('order-accepted-by-courier');

        echo 'actionAcceptOrderByCourier';
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
