<?php

use common\helpers\Internationalization;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

/* @var $order \common\models\db\ShopOrder */

$this->registerJs(<<<JS
function recalcComponentContentFrame() {
    var gapSizePerc = 20;

    var paneHeight = $('.order-components').height();
    var cornerHeight = $('.b-c-left-top').height();
    
    var gapSizePx = cornerHeight / 100 * gapSizePerc;
    
    var lineHeight = paneHeight - cornerHeight * 2 - gapSizePx * 2;
    
    $('.b-c-left-line').css(
        {
            'top': cornerHeight + gapSizePx + 'px',
            'height': lineHeight + 'px'
        } 
    ).show();

    $('.b-c-right-line').css(
        {
            'top': cornerHeight + gapSizePx + 'px',
            'height': lineHeight + 'px'
        } 
    ).show();
}

recalcComponentContentFrame();

$(window).resize(function() {
    recalcComponentContentFrame();
});

/*setTimeout(function() {
  location = location;
}, 12000);*/

JS
);

//TODO: переместить
$changeStatusUrl = json_encode(Url::to(['/setup/shop-order/status-change']));

//TODO: обработка ошибок типа 404
$this->registerJs(<<<JS
gl.functions.changeOrderStatus = function(shopOrderModelId, newStatusName) {
    $.post($changeStatusUrl,
        {shopOrderModelId: shopOrderModelId, newStatusName: newStatusName},
        function (data) {
            window.location = window.location;
        }
    );
}
JS
);

$this->registerCss(<<<CSS
button {
    color: black;
}
CSS
);

$shopOrderModelId = $order->id;
$newStatusName = json_encode('order-cancelled-by-user');

//TODO: выбирать по дате!!!
$lastStatus = $order->shopOrderStatuses[count($order->shopOrderStatuses) - 1];
//if ($lastStatus->type == 'offer-sent-to-customer') {
    $this->registerCssFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.css', [
        'integrity' => 'sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==',
        'crossorigin' => '',
    ]);
    $this->registerJsFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', [
        'integrity' => 'sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==',
        'crossorigin' => '',
    ]);

    //$mpData = Yii::$app->mapHandler->getStartPointParamsForUser();
    $mpMarksJs = json_encode(Yii::$app->mapHandler->getUserMarkers());

    $userGeoPosition = (new \common\models\Geo())->getUserGeoPosition();
    $mpData['latitude'] = $userGeoPosition['lat'];
    $mpData['longitude'] = $userGeoPosition['lon'];
    $mpData['zoom'] = 10;

    $this->registerJs(<<<JS
    var defaultMarks = $mpMarksJs;

    var map = L.map('place-map').setView([{$mpData['latitude']}, {$mpData['longitude']}], {$mpData['zoom']});
    
    if (defaultMarks.length) {
        var latLng = L.latLng(defaultMarks[0].latitude, defaultMarks[0].longitude);
        addMarker({latlng:latLng});
    }

	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap<\/a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA<\/a>',
    	maxZoom: 18
	}).addTo(map);

	function addMarker(e) {
        new L.marker(e.latlng, {draggable:true}).addTo(map);
    }

JS
    );

//}

?>
<main id="shop-order">
    <div class="top-title"><?= Yii::t('app', 'Your order') ?></div>
    <div class="order-info">
        <!--<img class="o-img" alt="" src="/img/order-desc.png">-->
        <div class="o-content">
            <?php
            echo Html::encode(Yii::t('app', 'Name: {name}', ['name' => Yii::t('app', 'Composite Pizza')])) . '<br>';
            echo Html::encode(Yii::t('app', 'Your order ID: {id}', ['id' => $order->order_uid])) . '<br>';
            $link = Html::a(Yii::t('app', 'open'), $order->getOrderUrl(), [
                'title' => Yii::t('app', 'Open in the new window'),
                'target' => '_blank',
                'class' => 'link-revert',
            ]);
            echo Yii::t('app', 'Order permanent link: {url}', ['url' => $link]) . '<br>';
            /*echo Html::encode(Yii::t('app', 'Created at: {created_at}',
                    ['created_at' => Yii::$app->formatter->asDatetime($order->created_at)])) . '<br>';*/

            echo Html::encode(Yii::t('app', 'Price: {price}',
                    ['price' => Internationalization::getPriceCaption($order->getTotalPrice())])) . '<br>';

            /*foreach ($order->shopOrderStatuses as $status) {
                echo 'Статус: ' . ' создан ' . Yii::$app->formatter->asDatetime($order->created_at) . '<br>';
            }*/

            echo 'Создан ' . Yii::$app->formatter->asDatetime($order->created_at) . '<br>';

            //echo '<div class="status-hint">' . Yii::t('app', 'Wait for approval') . '</div>';

            /*foreach ($order->shopOrderComponents as $component) {
                echo 'Компонент: ' . $component->name . ' цена ' . $component->order_price . '<br>';
            }*/
            ?>
        </div>
    </div>

    <div class="statuses">
        <div class="title"><?= Yii::t('app', 'Your order status') ?></div>
        <?php /*if ($lastStatus->type == 'offer-sent-to-customer'): */?>
        <?php if (1): ?>
            <div id="place-map" style="height:250px;width:100%;"></div>
        <?php endif; ?>
        <div class="info">
            <?php
            if ($lastStatus->type == 'created') {
                echo 'Заказ создан, ждите подтверждения от пиццерий';
            } elseif ($lastStatus->type == 'offer-sent-to-customer') {
                echo 'Пиццерии, подтвердившие заказ:<br>';
                //foreach ($order->users as $user) {
                $pz = $order->users[0];
                echo '<div class="approved-pizzerias">'
                    . '<div class="pizzeria-name">' . $pz->profile->name . '</div>'
                    . '<button class="btn-approve-order" onclick="return false;">Заказать</button>'
                    . '</div>';
                //}
            } elseif ($lastStatus->type == 'order-cancelled-by-user') {
                echo 'Заказ отменен вами.';
            }
            ?>
        </div>
        <br>
        <div id="map-"></div>
        <br>
        <?php if ($lastStatus->type != 'order-cancelled-by-user'): ?>
            <button class="decline-order"
                    onclick='gl.functions.changeOrderStatus(<?= $shopOrderModelId ?>, <?= $newStatusName ?>);return false;'>
                Отменить заказ
            </button>
        <?php endif; ?>
    </div>

    <div class="order-components">

        <div class="bg"></div>

        <img class="b-c-left-top" alt="" src="/img/menu/itm-left-top.svg">
        <img class="b-c-right-top" alt="" src="/img/menu/itm-right-top.svg">
        <img class="b-c-left-bottom" alt="" src="/img/menu/itm-left-bottom.svg">
        <img class="b-c-right-bottom" alt="" src="/img/menu/itm-right-bottom.svg">

        <div class="b-c-top-line"></div>
        <div class="b-c-bottom-line"></div>
        <div class="b-c-left-line"></div>
        <div class="b-c-right-line"></div>

        <div class="o-content">
            <div class="subheader"><?= Yii::t('app', 'Components') ?></div>

            <?php foreach ($order->shopOrderComponents as $component): ?>
                <div class="subelement">
                    <div class="title"><?= Html::encode($component->name) ?></div>
                    <div class="dots">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
                        . . . . . . . . . . . . .
                    </div>
                    <div class="price"><?= $component->order_price
                            ? Html::encode($component->order_price)
                            : ('<span class="text-warning" title="' . Html::encode(Yii::t('app',
                                    'For free')) . '">0.00</span>') ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
