<?php

namespace backend\controllers;

use common\models\db\CoWorker;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use common\models\shop_order\ShopOrderOrders;
use yii\helpers\ArrayHelper;
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

    public function actionIndex($worker_uid)
    {
        if (!$workerObj = CoWorker::findOne(['worker_site_uid' => $worker_uid])) {
            throw new NotFoundHttpException();
        }

        return $this->render('index', [
            'worker' => $workerObj,
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

        /*if ($type == 'courier') {
            if ($newShopOrders = ShopOrderStatus::find()
                ->select('shop_order_id')
                ->andWhere(['type' => 'offer-accepted-with-courier'])
                ->groupBy('shop_order_id')
                ->orderBy(['shop_order_id' => SORT_DESC])
                ->asArray()
                ->all()
            ) {
                $orderIds = ArrayHelper::getColumn($newShopOrders, 'shop_order_id');
                $orderObjs = ShopOrder::findAll($orderIds);
                foreach ($orderObjs as $shopOrder) {
                    $components = [];
                    if ($shopOrder->shopOrderComponents) {
                        foreach ($shopOrder->shopOrderComponents as $soComponent) {
                            $components[] = [
                                // Данные непосредственно на момент подтверждения заказа
                                'on_deal' => ArrayHelper::toArray($soComponent),
                                // Данные на текущий момент
                                'on_current' => ArrayHelper::toArray($soComponent->component),
                            ];
                        }
                    }
                    $result['orders'][] = [
                        'info' => ArrayHelper::toArray($shopOrder),
                        'components' => $components,
                    ];
                }
            }
        } elseif ($type == 'cook') {
            if ($newShopOrders = ShopOrderStatus::find()
                ->select('shop_order_id')
                ->andWhere(['type' => 'offer-sent-to-cook'])
                ->groupBy('shop_order_id')
                ->orderBy(['shop_order_id' => SORT_DESC])
                ->asArray()
                ->all()
            ) {
                $orderIds = ArrayHelper::getColumn($newShopOrders, 'shop_order_id');
                $orderObjs = ShopOrder::findAll($orderIds);
                foreach ($orderObjs as $shopOrder) {
                    $components = [];
                    if ($shopOrder->shopOrderComponents) {
                        foreach ($shopOrder->shopOrderComponents as $soComponent) {
                            $components[] = [
                                // Данные непосредственно на момент подтверждения заказа
                                'on_deal' => ArrayHelper::toArray($soComponent),
                                // Данные на текущий момент
                                'on_current' => ArrayHelper::toArray($soComponent->component),
                            ];
                        }
                    }
                    $result['orders'][] = [
                        'info' => ArrayHelper::toArray($shopOrder),
                        'components' => $components,
                    ];
                }
            }
        } else {    // default: новый (created) заказ
            $shopOrders = new ShopOrderOrders();
            $result['orders'] = $shopOrders->getActiveOrders($worker_uid);
            $result['status'] = 'success';
        }*/

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

    public function actionAcceptOrder()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'error'];

        $orderId = \Yii::$app->request->post('id');
        $type = \Yii::$app->request->post('type');

        if ($type == 'courier') {
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                $shopOrderStatus->type = 'offer-accepted-with-courier';
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                //$shopOrderStatus->save();

                $result['status'] = 'success';
            }
        } elseif ($type == 'cook') {
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                $shopOrderStatus->type = 'offer-accepted-with-cook';
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                //$shopOrderStatus->save();

                $result['status'] = 'success';
            }
        } else {    // новый заказ
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                //$shopOrderStatus->type = 'offer-sent-to-customer';
                $shopOrderStatus->type = 'offer-sent-to-cook';
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                //$shopOrderStatus->save();

                $result['status'] = 'success';
            }
        }

        return $result;
    }

    public function actionPassOrderToCourier()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'error'];

        $orderId = \Yii::$app->request->post('id');
        $type = \Yii::$app->request->post('type');

        if ($type == 'cook') {
            if ($shopOrder = ShopOrder::findOne($orderId)) {
                //TODO: проверять, может уже занят заказ
                $shopOrderStatus = new ShopOrderStatus();
                $shopOrderStatus->type = 'offer-accepted-with-courier';
                $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
                //$shopOrderStatus->save();

                $result['status'] = 'success';
            }
        } else {
            // unknown status
        }

        return $result;
    }
}
