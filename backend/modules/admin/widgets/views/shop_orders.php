<?php

use backend\modules\admin\widgets\models\ShopOrderHtmlElemData;

/**
 * @var \yii\web\View $this
 * @var ShopOrderHtmlElemData $shopOrderHtmlElemData[]
 */

$htmlOrders = '';

if (!$shopOrderHtmlElemData) {
    echo Yii::t('app', 'No orders yet.');
    return;
}

foreach ($shopOrderHtmlElemData as $statusType => $orderList) {
    //$orderList->
}

/*if (!$shopOrderList) {
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

//            foreach ($orderData['status_info_list'] as $status) {
//                $htmlOrderList .= '<div class="order-status">' . $status . '</div>';
//            }
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

echo $htmlOrders;*/
