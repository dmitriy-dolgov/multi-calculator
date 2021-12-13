<?php

/**
 * @var $this      yii\web\View
 * @var $modelUser common\models\db\User
 */


$shopOrderList = [];

/** @var \common\models\db\ShopOrder $modelShopOrder */
//foreach ($modelUser->getShopOrders0()->orderBy(['id' => SORT_DESC])->limit(10)->all() as $modelShopOrder) {
foreach ($modelUser->getShopOrders0()->orderBy(['id' => SORT_DESC])->all() as $modelShopOrder) {
    $orderData['amount_of_pizzerias'] = $modelShopOrder->getAmountOfUsers();
    $orderData['order_data'] = $modelShopOrder;

    //TODO: что за костыль с user_id ?
    $shoStatuses = $modelShopOrder->getShopOrderStatuses()->andWhere([
        'user_id' => $modelUser->id,
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

//print_r($shopOrderList);exit;

foreach ($shopOrderList as $shoElem) {
    $this->render('_orders-for-user_order-element');
}

$htmlOrders = '';

if (!$shopOrderList) {
    $htmlOrders = Yii::t('app', 'No orders');
}

foreach ($shopOrderList as $orderData) {
    $htmlOrderAmount = '<div class="order-amount-caption">'
        . Yii::t('app', 'Total pizzerias: {amount}',
            ['amount' => $orderData['amount_of_pizzerias']])
        . '</div>';
    $htmlOrderList = '';
    if ($orderData['status_info_list']) {
        foreach ($orderData['status_info_list'] as $statusType => $statusInfo) {

            /*foreach ($orderData['status_info_list'] as $status) {
                $htmlOrderList .= '<div class="order-status">' . $status . '</div>';
            }*/
        }
    } else {
        $htmlOrderList = Yii::t('app', 'No order statuses');
    }

    $htmlOrders .= '<div class="wrapper-order-fold">'
        . '<button class="btn btn-default btn-order-info" onclick="gl.functions.unwrapOrderInfo(this)">' . Yii::t('app', 'Order № {order_uid}',
            ['order_uid' => $orderData['order_data']['order_uid']]) . '</button>'
        . '<div class="order-fold" style="display: none">' . $htmlOrderAmount . $htmlOrderList . '</div>'
        . '</div>';
}

echo $htmlOrders;
