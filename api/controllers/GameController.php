<?php

namespace api\controllers;

use common\models\shop_order\ShopOrderOrders;
use yii\web\Controller;

class GameController extends Controller
{
    //public $layout = '@api/views/layouts/demo';
    public $layout = '@api/views/layouts/game';


    public function actionIndex()
    {
        //pizza-admin.local/game/index

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
