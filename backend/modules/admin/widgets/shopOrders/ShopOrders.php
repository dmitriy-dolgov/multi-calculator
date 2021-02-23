<?php

namespace backend\modules\admin\widgets\shopOrders;

use backend\modules\admin\widgets\shopOrders\models\ShopOrderHtmlElemData;
use common\models\db\User;
use Yii;
use yii\base\Widget;


class ShopOrders extends Widget
{
    /**
     * Максимальное кол-во показов для обработки.
     * TODO: это временный показатель
     */
    const MAX_ORDERS_TEMP = 7;


    /** @var User пользователь данных о заказах */
    public $modelUser;


    public function run()
    {
        $shopOrderHtmlElemData = [];

        $ordersInfo = $this->getOrdersInfo();

        $ordersByStatusType = $ordersInfo['orderListGroupedByStatuses'];
        foreach ($ordersByStatusType as $orderStatusType => $orderList) {
            $shopOrderHtmlElemData[] = Yii::createObject(ShopOrderHtmlElemData::class,
                [
                    $orderStatusType,
                    $orderList,
                    //'orderStatusType' => $orderStatusType,
                    //'orderStatusName' => ShopOrderStatus::getStatusNameByType($orderStatusType),
                    //'orderList' => $orderList,
                ]);
        }

        return $this->render('shop_orders', [
                'shopOrderHtmlElemData' => $shopOrderHtmlElemData,
            ]
        );
    }

    protected function getOrdersInfo()
    {
        $orderListGroupedByStatuses = [];

        //TODO: пересмотреть зависимость $timeCreated и $timeLastStatus от порядкового номера идентификатова
        $timeCreated = false;
        $timeLastStatus = false;

        $totalOrderList = $this->modelUser->getShopOrders0()->orderBy([
            //'created_at' => SORT_DESC,
            'id' => SORT_DESC,
        ])->limit(self::MAX_ORDERS_TEMP)->all();
        //echo print_r($totalOrderList, true);exit;
        foreach ($totalOrderList as $order) {
            if ($statusList = $order->getShopOrderStatuses()->orderBy(['id' => SORT_DESC])->limit(5)->asArray()->all()) {
                /*$orderListGroupedByStatuses[$statusList[count($statusList) - 1]['type']][] = [
                    'order' => $order,
                    //'statusList' => $statusList,
                ];*/

                if ($order->ifBelongsToUser($this->modelUser->id)) {
                    $orderListGroupedByStatuses[$statusList[count($statusList) - 1]['type']][] = $order;

                    // Order creation time
                    if ($statusList[count($statusList) - 1]['type'] == 'created') {
                        $timeCreated = $statusList[count($statusList) - 1]['accepted_at'];
                    }
                }
            }
        }

        // Find last order event time
        if (!empty($statusList)) {
            $timeLastStatus = $statusList[count($statusList) - 1]['accepted_at'];
        }

        return [
            'timeCreated' => $timeCreated,
            'timeLastStatus' => $timeLastStatus,
            'orderListGroupedByStatuses' => $orderListGroupedByStatuses,
        ];
    }

    protected function getHtmlInfo(array $orderListGroupedByStatuses)
    {
        $htmlInfo = [];

        foreach ($orderListGroupedByStatuses as $statusId => $orders) {
            $htmlNewElement = [
                'status' => [
                    'id' => $statusId,
                    'name'
                ]
            ];
        }

        return $htmlInfo;
    }

    protected function getHtmlInfoOld()
    {
        $shopOrderList = [];

        /** @var \common\models\db\ShopOrder $modelShopOrder */
        foreach ($this->modelUser->getShopOrders0()->orderBy(['id' => SORT_DESC])->all() as $modelShopOrder) {
            $orderData['amount_of_pizzerias'] = $modelShopOrder->getAmountOfUsers();
            $orderData['order_data'] = $modelShopOrder;

            //TODO: что за костыль с user_id ?
            $shoStatuses = $modelShopOrder->getShopOrderStatuses()->andWhere([
                'user_id' => $this->modelUser->id,
                'shop_order_id' => $modelShopOrder->getPrimaryKey()
            ])->all();

            //$orderData = [];
            foreach ($shoStatuses as $status) {
                if (!isset($shopOrderList[$status->type])) {
                    $shopOrderList[$status->type] = [
                        'name' => $status->getStatusName(),
                        'list' => [],
                    ];
                }
                /*if (!isset($orderData[$status->type])) {
                    $orderData[$status->type] = [
                        'name' => $status->getStatusName(),
                        'list' => [],
                    ];
                }*/
                $shopOrderList[$status->type]['list'][] = $status;
            }

            //$shopOrderList[] = $orderData;
        }

        /*return $this->render('shop_orders', [
            //$shopOrderList[]
        ]);*/
    }
}