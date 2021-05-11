<?php

namespace backend\controllers;

use common\models\db\CoWorker;
use common\models\db\ShopOrder;
use common\models\shop_order\ShopOrderAcceptorders;
use common\models\shop_order\ShopOrderCourier;
use common\models\shop_order\ShopOrderMaker;
use common\models\shop_order\ShopOrderOrders;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class WorkerController extends Controller
{
    public $layout = '@backend/views/layouts/worker';

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

    public function actionIndex($workerUid)
    {
        if (!$workerObj = CoWorker::findOne(['worker_site_uid' => $workerUid])) {
            throw new NotFoundHttpException();
        }

        $coWorkerFunctions = \yii\helpers\ArrayHelper::map($workerObj->coWorkerFunctions, 'id', 'name');

        $orders = [];

        foreach ($workerObj->coWorkerFunctions as $cwFunction) {
            $className = $cwFunction->getCoWorkerFunctionClassById();
            if (!class_exists($className)) {
                \Yii::error('Class "' . $className . '" does not exist.');
                throw new NotFoundHttpException();
            }

            $shopOrders = new $className($workerUid);
            $orders[$cwFunction->id] = $shopOrders->getActiveOrders();
        }

        return $this->render('index', [
            'worker' => $workerObj,
            'coWorkerFunctions' => $coWorkerFunctions,
            'orders' => $orders,
        ]);
    }

    //TODO: раскидать по моделям
    public function actionGetActiveOrders($worker_uid, $type = false)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'undefined', 'orders' => []];

        $className = $type ?: 'Orders';
        $className = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $className));
        $className = 'common\models\shop_order\ShopOrder' . $className;

        if (!class_exists($className)) {
            \Yii::error('Class "' . $className . '" does not exist.');
            throw new NotFoundHttpException();
        }

        $shopOrders = new $className;
        $result['orders'] = $shopOrders->getActiveOrders($worker_uid);
        $result['status'] = 'success';

        return $result;
    }

    /*protected function getUserByWorkerUid($worker_uid)
    {
        if (!$worker = CoWorker::findOne(['worker_site_uid' => $worker_uid])) {
            //TODO: проверить как работает с AJAX
            throw new NotFoundHttpException(\Yii::t('app', 'No such worker: {worker_uid}',
                ['worker_uid' => $worker_uid]));
        }
        //TODO: пользователя может не быть
        return User::findOne($worker->user_id);
    }*/

    /**
     * Новый заказ.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionAcceptOrderByMaker()
    {
        //pizza-admin.local/worker/accept-order-by-maker

        Yii::$app->response->format = Response::FORMAT_JSON;

        $workerUid = Yii::$app->request->post('workerUid');
        //TODO: заменить здесь и в других местах на order UID
        $orderId = Yii::$app->request->post('orderId');

        try {
            $shopOrderMaker = new ShopOrderMaker($workerUid);
            $result = $shopOrderMaker->acceptOrder($orderId);

            if ($result['status'] == 'success') {
                //TODO: ShopOrder::findOne() дублируется в $shopOrderMaker->acceptOrder() - проверить есть ли проблема и решить
                $shopOrder = ShopOrder::findOne($orderId);
                $orderData = ShopOrderAcceptorders::getAnOrder($shopOrder);
                $orderData['status'] = 'accepted-by-maker';
                $orderHtml = $this->renderPartial('@backend/views/worker/_order_element',
                    ['worker' => $shopOrderMaker->getWorkerObj(), 'orderData' => $orderData]);
                $result['order_html'] = $orderHtml;
            }
        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

    public function actionAcceptOrderByCourier()
    {
        //pizza-admin.local/worker/accept-order-by-courier

        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'error'];

        $workerUid = Yii::$app->request->post('workerUid');
        //TODO: заменить здесь и в других местах на order UID
        $orderId = Yii::$app->request->post('orderId');

        try {
            $shopOrderCourier = new ShopOrderCourier($workerUid);
            $result = $shopOrderCourier->acceptOrder($orderId);

            if ($result['status'] == 'success') {
                //TODO: ShopOrder::findOne() дублируется в $shopOrderMaker->acceptOrder() - проверить есть ли проблема и решить
                $shopOrder = ShopOrder::findOne($orderId);
                $orderData = ShopOrderAcceptorders::getAnOrder($shopOrder);
                $orderData['status'] = 'accepted-by-courier';
                $orderHtml = $this->renderPartial('@backend/views/worker/_order_element',
                    ['worker' => $shopOrderCourier->getWorkerObj(), 'orderData' => $orderData]);
                $result['order_html'] = $orderHtml;
            }
        } catch (\Exception $e) {
            //Yii::$app->log->  // прикрутить
            $result['status'] = 'error';
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

    /*public function actionPassOrderToCourier()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'error'];

        $orderId = \Yii::$app->request->post('id');
        $type = \Yii::$app->request->post('type');

        if ($type == 'maker') {
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                $shopOrderStatus->type = 'accepted-by-courier';
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                //$shopOrderStatus->save();

                $result['status'] = 'success';
            }
        } else {
            // unknown status
        }

        return $result;
    }*/

    public function actionCourierArrived()
    {
        //pizza-admin.local/worker/courier-arrived

        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'error'];

        $workerUid = Yii::$app->request->post('workerUid');
        //TODO: заменить здесь и в других местах на order UID
        $orderId = Yii::$app->request->post('orderId');

        try {
            $shopOrderCourier = new ShopOrderCourier($workerUid);
            $result = $shopOrderCourier->courierArrived($orderId);
        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

    public function actionCompleteOrder()
    {
        //pizza-admin.local/worker/complete-order

        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'error'];

        $workerUid = Yii::$app->request->post('workerUid');
        //TODO: заменить здесь и в других местах на order UID
        $orderId = Yii::$app->request->post('orderId');

        try {
            $shopOrderCourier = new ShopOrderCourier($workerUid);
            $result = $shopOrderCourier->completeOrder($orderId);

            if ($result['status'] == 'success') {
                //TODO: ShopOrder::findOne() дублируется в $shopOrderMaker->acceptOrder() - проверить есть ли проблема и решить
                $shopOrder = ShopOrder::findOne($orderId);
                $orderData = ShopOrderAcceptorders::getAnOrder($shopOrder);
                $orderData['status'] = 'finished';
                $orderHtml = $this->renderPartial('@backend/views/worker/_order_element',
                    ['worker' => $shopOrderCourier->getWorkerObj(), 'orderData' => $orderData]);
                $result['order_html'] = $orderHtml;
            } else {
                //TODO: потенциальная обработка ошибки
                Yii::error('Error complete order!');
            }
        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }
}
