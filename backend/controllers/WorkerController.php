<?php

namespace backend\controllers;

use common\models\db\CoWorker;
use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use common\models\db\User;
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

    public function actionGetActiveOrders($worker_uid)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['status' => 'success', 'orders' => []];

        //$user = $this->getUserByWorkerUid($worker_uid);
        //if ($user->shopOrders) {
        //foreach ($user->shopOrders as $shopOrder) {

        $shopOrders = ShopOrder::find()->all();
        if ($shopOrders) {
            foreach ($shopOrders as $shopOrder) {
                if ($shopOrder->shopOrderStatuses) {
                    $isNew = true;
                    foreach ($shopOrder->shopOrderStatuses as $shopOrderStatus) {
                        if ($shopOrderStatus->type != 'created') {
                            $isNew = false;
                            break;
                        }
                    }
                    if ($isNew) {
                        $result['orders'][] = [
                            'id' => $shopOrder->id,
                            'order_uid' => $shopOrder->order_uid,
                            'created_at' => $shopOrder->created_at,
                            'deliver_customer_name' => $shopOrder->deliver_customer_name,
                            'deliver_address' => $shopOrder->deliver_address,
                            'deliver_phone' => $shopOrder->deliver_phone,
                            'deliver_email' => $shopOrder->deliver_email,
                            'deliver_comment' => $shopOrder->deliver_comment,
                        ];
                    }
                }
            }
        }

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

        if ($shopOrder = ShopOrder::findOne($orderId)) {
            //TODO: проверять, может уже занят заказ
            $shopOrderStatus = new ShopOrderStatus();
            $shopOrderStatus->type = 'offer-sent-to-customer';
            $shopOrder->link('shopOrderStatuses', $shopOrderStatus);
            //$shopOrderStatus->save();

            $result['status'] = 'success';
        }

        return $result;
    }
}
