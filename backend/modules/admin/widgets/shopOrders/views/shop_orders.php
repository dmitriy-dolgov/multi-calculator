<?php

use backend\modules\admin\widgets\shopOrders\models\ShopOrderHtmlElemData;
use yiister\gentelella\widgets\Accordion;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var ShopOrderHtmlElemData $shopOrderHtmlElemData []
 */

$this->registerCss(<<<CSS
.order-status-name {
    
}
CSS
);


//$htmlOrders = '';

if (!$shopOrderHtmlElemData) {
    echo Yii::t('app', 'No orders yet.');
    return;
}

?>
<?php
/** @var ShopOrderHtmlElemData $orderByTypeList */
foreach ($shopOrderHtmlElemData as $orderByTypeList):
    $accordionItems = [];
    foreach ($orderByTypeList->getOrderList() as $order) {
        $accordionItems[] = [
            'title' => $order->order_uid . '<br>'. Html::encode($order->created_at),
            'content' => (function () use ($order) {
                $content = '<ul>';
                foreach ($order->shopOrderStatuses as $shOrdStatus) {
                    $content .= '<li>' . $shOrdStatus->getStatusName() . '</li>';
                }
                $content .= '</ul>';

                return $content;
            })(),
        ];
    }
    ?>
    <fieldset>
        <legend>
            <button class="order-status-name"><?= Html::encode($orderByTypeList->getOrderStatusName()) ?></button>
        </legend>
        <?= Accordion::widget([
            'items' => $accordionItems,
            /*'options' => ['tag' => 'div'],
            'itemOptions' => ['tag' => 'div'],
            'headerOptions' => ['tag' => 'h3'],
            'clientOptions' => ['collapsible' => false],*/
        ]) ?>
        <?php /*foreach ($orderByTypeList->getOrderList() as $order): */
        ?><!--
            <div class="row">
                <div class="col-md-3">
                    <button>
                        <div class="order-uid"><?/*= Html::encode($order->order_uid) */
        ?></div>
                        <div class="order-datetime"><?/*= Html::encode($order->created_at) */
        ?></div>
                    </button>
                </div>
                <div class="col-md-9">
                    <ul>
                        <?php /*foreach ($order->shopOrderStatuses as $shOrdStatus): */
        ?>
                            <li>
                                <?/*= $shOrdStatus->getStatusName() */
        ?>
                            </li>
                        <?php /*endforeach; */
        ?>
                    </ul>
                </div>
            </div>
        --><?php /*endforeach; */
        ?>
    </fieldset>
<?php
endforeach;
?>


<?php
/*if (!$shopOrderList) {
$htmlOrders = Yii::t('app', 'No orders');
}

foreach ($shopOrderList as $orderData) {
$htmlOrderAmount = '
        < div class="order-amount-caption" > '
    . Yii::t('app', 'Total pizzerias: {
            amount}',
    ['amount' => $orderData['amount_of_pizzerias']])
    . '
        </div > ';
$htmlOrderList = '';
if ($orderData['status_info_list']) {
foreach ($orderData['status_info_list'] as $statusType => $statusInfo) {

//            foreach ($orderData['status_info_list'] as $status) {
//                $htmlOrderList .= '
        < div class="order-status" > ' . $status . '</div > ';
//            }
}
} else {
$htmlOrderList = Yii::t('app', 'No order statuses');
}

$htmlOrders .= '
        < div class="wrapper-order-fold" > '
    . '
    <button class="btn btn-default btn-order-info" onclick = "gl.functions.unwrapOrderInfo(this)" > ' . Yii::t('app', 'Order
        № {
            order_uid}',
        ['order_uid' => $orderData['order_data']['order_uid']]) . '
        </button >
        '
    . '
    <div class="order-fold" style = "display: none" > ' . $htmlOrderAmount . $htmlOrderList . '</div >
        '
    . '
</div > ';
}

echo $htmlOrders;*/
?>
    <!--<div style="width: 100%">
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
    </div>-->

<?php /* ?>
<div style="width: 100%">
    <button style="width: 100%; text-align: center">Завершен &gt;</button>
    <button class="btn btn-default" style="width: 80%; float: right">
        <div style="font-weight: bold">omhD48nslkfIsvM</div>
        <div style="font-style: italic; margin-left: 2em">2021-02-19 17:11:08</div>
    </button>
    <ul style="width: 60%; float: right">
        <li>Создан (2021-01-17 20:50:49)</li>
        <li>Принят поваром (2021-01-17 20:52:25)</li>
        <li>Принят курьером (2021-01-17 20:53:09)</li>
        <li>Доставлен пользователю (2021-01-17 21:01:13)</li>
    </ul>
    <div style="width: 80%; float: right">
        <button class="btn btn-success" style="width: 30%;color:red">Установить отказ</button>
        <select>
            <option>- Причина -</option>
            <option>Нет средств на карте</option>
            <option>Не качественна пицца</option>
        </select>
    </div>
</div>
<div style="width: 100%">
    <button style="width: 100%; text-align: center">Завершен (2021-02-19 22:00:04) &lt;</button>
</div>
<div style="width: 100%">
    <button style="width: 100%; text-align: center">Завершен (2021-02-18 15:10:59) &lt;</button>
</div>
<div style="width: 100%; margin-top: 20px; display: inline-block">
    <button style="width: 100%; text-align: center">Создан <span style="color:red;font-weight: bold">(устарел ?)</span> &gt;</button>
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
<?php */ ?>