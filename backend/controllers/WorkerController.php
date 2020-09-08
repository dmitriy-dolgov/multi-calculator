<?php

namespace backend\controllers;

use common\models\db\ShopOrderUser;
use common\models\db\User;
use common\models\shop_order\ShopOrderAcceptorders;
use common\models\shop_order\ShopOrderMaker;
use frontend\sse\CustomerWaitResponseOrderHandling;
use Yii;
use common\models\db\CoWorker;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use common\models\shop_order\ShopOrderOrders;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
        $orderId = Yii::$app->request->post('orderId');

        try {
            $shopOrderMaker = new ShopOrderMaker($workerUid);
            $result = $shopOrderMaker->acceptOrder($orderId);

            if ($result['status'] == 'success') {
                //TODO: ShopOrder::findOne() дублируется в $shopOrderMaker->acceptOrder() - проверить есть ли проблема и решить
                $shopOrder = ShopOrder::findOne($orderId);
                $orderData = ShopOrderAcceptorders::getAnOrder($shopOrder);
                $orderData['status'] = 'offer-accepted-by-maker';
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
        //pizza-admin.local/worker/accept-order-by-maker

        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'error'];

        $workerUid = Yii::$app->request->post('worker_uid');
        if (!$coWorker = CoWorker::find()->select([
            'id',
            'user_id'
        ])->andWhere(['worker_site_uid' => $workerUid])->one()) {
            Yii::error('Co-worker not found: `' . $workerUid . '``');
            throw new NotFoundHttpException('Co-worker not found.');
        }

        $orderId = Yii::$app->request->post('id');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                $currentTimestamp = date('Y-m-d H:i:s');

                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                $shopOrderStatus->type = 'offer-accepted-by-courier';
                $shopOrderStatus->user_id = $coWorker->user_id;
                $shopOrderStatus->accepted_at = $currentTimestamp;
                $shopOrderStatus->accepted_by = $coWorker->id;
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);

                // Сигнал отправившему заявку пользователю что пицца принята в разработку
                /*$alertUrl = \common\helpers\Web::getUrlToCustomerSite()
                    . Url::to([
                        '/make-order/accept-order-by-courier',
                        'orderUid' => $shopOrder->order_uid,
                        'merchantId' => $coWorker->user_id,
                        'courierId' => $coWorker->id,
                    ]);*/

                $alertUrl = \common\helpers\Web::getUrlToCustomerSite()
                    . Url::to([
                        '/make-order/order-accept',
                        'type' => 'accepted-by-courier',
                        'orderUid' => $shopOrder->order_uid,
                        'merchantId' => $coWorker->user_id,
                        'courierId' => $coWorker->id,
                    ]);

                if (file_get_contents($alertUrl) == 'success') {
                    $result['status'] = 'success';
                } else {
                    //throw new \Exception(Yii::t('app', "Cound't send notice about an order to user!"));
                    Yii::error("Cound't send notice about an order to user! Url: " . $alertUrl);
                    //TODO: !!!!!!!! обработка статуса 'warning'
                    $result['status'] = 'warning';
                    $result['msg'] = Yii::t('app', "Cound't send notice about an order to user!");
                }
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $result['msg'] = $e->getMessage();
            //throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $result['msg'] = $e->getMessage();
            //throw $e;
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
                $shopOrderStatus->type = 'offer-accepted-by-courier';
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                //$shopOrderStatus->save();

                $result['status'] = 'success';
            }
        } else {
            // unknown status
        }

        return $result;
    }*/
}
