<?php

namespace backend\controllers;

use common\models\db\CoWorker;
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

        $user = $this->getUserByWorkerUid($worker_uid);
        if ($user->shopOrders) {
            foreach ($user->shopOrders as $shopOrder) {
                if ($shopOrder->shopOrderStatuses) {
                    $isNew = true;
                    foreach ($shopOrder->shopOrderStatuses as $shopOrderStatus) {
                        if ($shopOrderStatus != 'created') {
                            $isNew = false;
                            break;
                        }
                    }
                    if ($isNew) {
                        $result[] = $shopOrder;
                    }
                }
            }
        }

        return $result;
    }

    protected function getUserByWorkerUid($worker_uid)
    {
        if (!$worker = CoWorker::findOne(['worker_uid' => $worker_uid])) {
            //TODO: проверить как работает с AJAX
            throw new NotFoundHttpException(\Yii::t('app', 'No such worker: {worker_uid}',
                ['worker_uid' => $worker_uid]));
        }

        return User::findOne($worker->user_id);
    }
}
