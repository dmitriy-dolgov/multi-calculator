<?php

use backend\modules\admin\widgets\shopOrders\models\ShopOrderHtmlElemData;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var ShopOrderHtmlElemData $shopOrderHtmlElemData []
 */

$htmlOrders = '';

if (!$shopOrderHtmlElemData) {
    echo Yii::t('app', 'No orders yet.');
    return;
}
?>
<?php
/** @var ShopOrderHtmlElemData $orderByTypeList */
foreach ($shopOrderHtmlElemData as $orderByTypeList):
    ?>
    <div class="row">
        <div class="col-md-3"><?= Html::encode($orderByTypeList->getOrderStatusName()) ?></div>
        <div class="col-md-9">
            <?php foreach ($orderByTypeList->getOrderList() as $order): ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="order-uid"><?= Html::encode($order->order_uid) ?></div>
                        <div class="order-datetime"><?= Html::encode($order->created_at) ?></div>
                    </div>
                    <div class="col-md-9">
                        <?php foreach ($order->): ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>

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
?>
<div style="width: 100%">
    <button style="width: 100%; text-align: center">Оплачен пользователем</button>
    <button class="btn btn-default" style="width: 80%; float: right">
        <div style="font-weight: bold">yN-jDwbby1nRb7d</div>
        <div style="font-style: italic; margin-left: 2em">2021-01-17 20:50:49</div>
    </button>
    <ul style="width: 60%; float: right">
        <li>Создан (2021-01-17 20:50:49)</li>
        <li>Принят поваром (2021-01-17 21:25:27)</li>
        <li>Принят курьером (2021-01-17 21:52:02)</li>
        <li>Доставлен пользователю (2021-01-17 22:28:22)</li>
        <li>Оплачен пользователем (2021-01-17 22:30:30)</li>
    </ul>
    <button class="btn btn-success" style="width: 80%; float: right">Завершить</button>
</div>
<div style="width: 100%; margin-top: 20px; display: inline-block">
    <button style="width: 100%; text-align: center">Создан</button>
    <button class="btn btn-default" style="width: 80%; float: right">
        <div style="font-weight: bold">BD_8pCIrhF2gE1T</div>
        <div style="font-style: italic; margin-left: 2em">2021-01-19 19:08:35</div>
    </button>
    <ul style="width: 60%; float: right">
        <li>Создан (2021-01-17 20:50:49)</li>
    </ul>
    <div style="width: 80%; float: right">
        <button class="btn btn-success" style="width: 30%">Принять поваром</button>
        <select>
            <option> - Выбрать повара -</option>
            <option>Ladlen</option>
            <option>daiviz</option>
            <option>335533</option>
            <option>id20013921</option>
        </select>
    </div>
</div>