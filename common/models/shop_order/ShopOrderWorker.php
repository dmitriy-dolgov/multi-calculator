<?php

namespace common\models\shop_order;

use yii\base\Component;

abstract class ShopOrderWorker extends Component
{
    abstract public function getActiveOrders($worker_uid);
}
