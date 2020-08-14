<?php

namespace common\models\shop_order;

abstract class ShopOrderWorker extends Component
{
    abstract public function getActiveOrders($worker_uid);
}
