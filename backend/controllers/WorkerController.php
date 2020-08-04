<?php

namespace backend\controllers;

use common\models\db\CoWorker;
use common\models\LoginForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class WorkerController extends Controller
{
    public $layout = '@backend/views/layouts/clean-simple';

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

        return $this->render('index', [
            'worker' => $workerObj,
        ]);
    }
}
