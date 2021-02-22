<?php

namespace backend\modules\admin\widgets\shopOrders\models;

use common\models\db\ShopOrder;
use common\models\db\ShopOrderStatus;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class ShopOrderHtmlElemData extends BaseObject
{
    /** @var string */
    protected $_orderStatusType;

    /** @var ShopOrder[] */
    protected $_orderList = [];

    /** @var string */
    protected $_orderStatusName;

    /** @var ShopOrderStatus[] */
    public $_orderStatusList = [];


    public function __construct(string $orderStatusType, array $orderList, $config = [])
    {
        $this->setOrderStatusType($orderStatusType);
        $this->setOrderList($orderList);

        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function getOrderStatusType(): string
    {
        return $this->_orderStatusType;
    }

    /**
     * @param string $orderStatusType
     */
    protected function setOrderStatusType(string $orderStatusType): void
    {
        $this->_orderStatusType = $orderStatusType;
    }

    /**
     * @return ShopOrder[]
     */
    public function getOrderList(): array
    {
        return $this->_orderList;
    }

    /**
     * @param ShopOrder[] $orderList
     */
    protected function setOrderList(array $orderList): void
    {
        $orderListNewKeys = ArrayHelper::getColumn($orderList, function ($orderElement) {
            //return [$orderElement->id => $orderElement];
            return $orderElement;
        });

        $this->_orderList = $orderListNewKeys;
    }

    /**
     * @return string
     */
    public function getOrderStatusName(): string
    {
        if (!$this->_orderStatusName) {
            $this->_orderStatusName = ShopOrderStatus::getStatusNameByType($this->getOrderStatusType());
        }

        return $this->_orderStatusName;
    }

    /**
     * @return ShopOrderStatus[]
     */
    public function getOrderStatusList($orderId): array
    {
        if (!isset($this->getOrderList()[$orderId])) {
            throw new \DomainException('No order with id ' . $orderId);
        }

        if (!isset($this->_orderStatusList[$orderId])) {
            /*$orderObj = $this->getOrderList()[$orderId];
            if (!isset($this->_orderStatusList[$orderId])) {
                if ($this->_orderStatusList === null) {
                    $this->_orderStatusList = $this->_orderList->modelUser->getShopOrders0()->orderBy(['created_at' => SORT_DESC])->all();
                }
            }*/

            //TODO: здесь остановился
            $orderObj = $this->getOrderList()[$orderId];
            //$this->_orderStatusList[$orderId] = $this->_orderList->modelUser->getShopOrders0()->orderBy(['created_at' => SORT_DESC])->all();
        }

        //$this->_orderStatusList[$orderId] = $orderObj->shopOrderStatuses;

        return $this->_orderStatusList[$orderId];
    }
}
