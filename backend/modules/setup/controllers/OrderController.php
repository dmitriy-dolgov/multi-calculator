<?php

namespace backend\modules\setup\controllers;

use backend\modules\vendor\models\Vendor;
use yii\web\Controller;

class OrderController extends Controller
{
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

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionList()
    {
        $orderList = \Yii::$app->user->identity->shopOrders ?? [];

        return $this->render('list', [
            'orderList' => $orderList,
        ]);
    }
}
