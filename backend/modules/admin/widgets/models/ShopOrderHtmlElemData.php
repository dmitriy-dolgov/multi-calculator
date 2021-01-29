<?php

namespace backend\modules\admin\widgets\models;

use common\models\db\ShopOrder;
use yii\base\BaseObject;

class ShopOrderHtmlElemData extends BaseObject
{
    /** @var string */
    private $_orderStatusType;

    /** @var ShopOrder[] */
    private $_orderInfoList = [];


    public function __construct(string $orderStatusType, array $orderInfoList, $config = [])
    {
        $this->setOrderStatusType($orderStatusType);
        $this->setOrderInfoList($orderInfoList);

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
    private function setOrderStatusType(string $orderStatusType): void
    {
        $this->_orderStatusType = $orderStatusType;
    }

    /**
     * @return ShopOrder[]
     */
    public function getOrderList(): array
    {
        return $this->_orderInfoList;
    }

    /**
     * @param ShopOrder[] $orderList
     */
    public function setOrderList(array $orderList): void
    {
        $this->_orderInfoList = $orderList;
    }

}
