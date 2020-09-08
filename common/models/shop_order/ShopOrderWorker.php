<?php

namespace common\models\shop_order;

use yii\base\Component;

abstract class ShopOrderWorker extends Component
{
    protected $workerUid;


    abstract public function getActiveOrders();

    public function __construct($workerUid, $config = [])
    {
        $this->workerUid = $workerUid;

        parent::__construct($config);
    }
}
