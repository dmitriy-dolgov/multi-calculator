<?php

namespace backend\modules\admin\widgets\models;

use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use yii\base\BaseObject;

class ShopOrderHtmlElemData extends BaseObject
{
    /** @var string */
    public $orderStatusType;

    /** @var ShopOrder[] */
    public $orderList = [];

    /** @var ShopOrderStatus[] */
    public $orderStatusList = false;


    public function __construct(string $orderStatusType, array $orderList, $config = [])
    {
        $this->setOrderStatusType($orderStatusType);
        $this->setOrderList($orderList);

        parent::__construct($config);
    }

    /**
     * @return string
     */
//    public function getOrderStatusType(): string
//    {
//        return $this->_orderStatusType;
//    }
//
//    /**
//     * @param string $orderStatusType
//     */
//    private function setOrderStatusType(string $orderStatusType): void
//    {
//        $this->_orderStatusType = $orderStatusType;
//    }
//
//    /**
//     * @return ShopOrder[]
//     */
//    public function getOrderList(): array
//    {
//        return $this->_orderList;
//    }
//
//    /**
//     * @param ShopOrder[] $orderList
//     */
//    public function setOrderList(array $orderList): void
//    {
//        $this->_orderList = $orderList;
//    }

    /**
     * @return ShopOrderStatus[]
     */
    public function getOrderStatusList(): array
    {
        if ($this->_orderStatusList === false) {
            $this->_orderStatusList = $this->_orderList->modelUser->getShopOrders0()->orderBy(['created_at' => SORT_DESC])->all();
        }

        return $this->_orderStatusList;
    }

    /**
     * @param ShopOrderStatus[] $orderStatusList
     */
    /*private function setOrderStatusList(array $orderStatusList): void
    {
        $this->_orderStatusList = $orderStatusList;
    }*/

}
