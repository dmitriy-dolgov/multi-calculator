<?php

/**
 * @var $this      yii\web\View
 * @var $modelUser common\models\db\User
 */

$shopOrderList = [];

/** @var ShopOrder $modelShopOrder */
foreach ($modelUser->getShopOrders0()->orderBy(['id' => SORT_DESC])->limit(10)->all() as $modelShopOrder) {
    $orderData['amount_of_pizzerias'] = $modelShopOrder->getAmountOfUsers();
    $orderData['order_data'] = $modelShopOrder;

    //TODO: что за костыль с user_id ?
    $shoStatuses = $modelShopOrder->getShopOrderStatuses()->andWhere([
        'user_id' => $modelUser->id,
        'shop_order_id' => $modelShopOrder->getPrimaryKey()
    ])->all();

    $orderData['status_list'] = [];
    foreach ($shoStatuses as $status) {
        $orderData['status_list'][] = $status->getStatusName();
    }

    $shopOrderList[] = $orderData;
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
    if ($orderData['status_list']) {
        foreach ($orderData['status_list'] as $status) {
            $htmlOrderList .= '<div class="order-status">' . $status . '</div>';
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
