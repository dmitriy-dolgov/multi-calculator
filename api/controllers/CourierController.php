<?php

namespace api\controllers;

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

class CourierController extends Controller
{
    public function actionIndex()
    {
        //courier-

        return $this->render('index');
    }

    public function actionVendor()
    {
        return $this->render('vendor');
    }

    public function actionCustomer()
    {
        return $this->render('customer');
    }
}
