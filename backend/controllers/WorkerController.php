<?php

namespace backend\controllers;

use common\models\db\CoWorker;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
}
