<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */

$this->registerCssFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.css', [
    'integrity' => 'sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==',
    'crossorigin' => '',
]);
$this->registerJsFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', [
    'integrity' => 'sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==',
    'crossorigin' => '',
]);

$this->registerJsFile(Url::to(['/js/leaflet/L.Icon.Pulse.js']), ['depends' => ['frontend\assets\VendorAsset']]);
$this->registerCssFile(Url::to(['/js/leaflet/L.Icon.Pulse.css']));

$this->registerCss(<<<CSS
#worker-place-map {
    width: 100%;
    height: 70vh;
}
CSS
);

$this->registerJsFile(Url::to(['/js/worker/geo.js']), ['depends' => ['backend\assets\WorkerAsset']]);

//TODO: перевод
$this->registerJs(<<<JS
    gl.data['geolocation-is-not-accessible'] = 'Geolocation Not accessible.';
JS
);

$jsStrings = [
    'worker/get-active-orders' => json_encode(Url::to(['worker/get-active-orders'])),
    'worker_uid' => json_encode(Yii::$app->request->get('worker_uid')),
];

$this->registerCss(<<<CSS
.order {
    margin-bottom: 20px;
}
CSS
);

$this->registerJs(<<<JS
    var elems = {
        '#orders-pane': $('#orders-pane')
    };

    if (gl.functions.orders.courier) {
        alert('"gl.functions.orders.courier" already set');
    }
    gl.functions.orders.courier = {};
    
    gl.functions.orders.courier.passOrderToCourier = function(id) {
        $.post('worker/pass-order-to-courier', {id:id,type:'courier'}, function(data) {
            alert('Заказ отправлен курьеру!');
            elems['#orders-pane'].find('.order[data-id=' + id + ']').fadeOut(400, function() {
              // TODO: to remove
              //this.remove();
            });
        });
    };

    gl.functions.orders.courier.acceptOrder = function(id) {
        $.post('worker/accept-order', {id:id,type:'courier'}, function(data) {
          if (data.status == 'success') {
              alert('Вы перешли в статус доставки.');
              /*elems['#orders-pane'].find('.order[data-id=' + id + ']').fadeOut(400, function() {
                  // TODO: to remove
                  //this.remove();
              });*/
              var html = 'Заказ исполняется вами! &nbsp;<button onclick="gl.functions.orders.courier.passOrderToCourier(' + id + ');return false;">Завершить</button>';
              elems['#orders-pane'].find('.order[data-id=' + id + ']').find('.btn-accept-order').replaceWith(html);
          } else {
              //TODO: to translate , maybe handle error
                gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
    
    gl.functions.orders.courier.declineOrder = function(id) {
        alert('В разработке');
    };

    //TODO: to translate
    gl.functions.orders.courier.putNewOrderToPane = function(order) {
        var info = order.info;
        var currentComponentsHtml = 'Компоненты:<br>';
        for (var id in order.components) {
            var cci = order.components[id].on_current;
            currentComponentsHtml += '<i>"' + cci.name + '"</i> Цена: ' + cci.price + ' р.';
            if (order.components[id].on_deal.amount && order.components[id].on_deal.amount > 1) {
                currentComponentsHtml += ' (' + order.components[id].on_deal.amount + ' шт)';
            }
            currentComponentsHtml += '<br>';
        }
        
        var html = '<div class="order" data-id="' + info.id + '">'
            + '<div class="o-info id">ID: ' + info.id + '</div>'
            + '<div class="o-info order_uid">UID: ' + info.order_uid + '</div>'
            + '<div class="o-info created_at">Создан: ' + info.created_at + '</div>'
            + '<div class="o-info deliver_customer_name">Имя: ' + info.deliver_customer_name + '</div>'
            + '<div class="o-info deliver_address">Адрес: ' + info.deliver_address + '</div>'
            + '<div class="o-info deliver_phone">Телефон: ' + info.deliver_phone + '</div>'
            + '<div class="o-info deliver_email">Email: ' + info.deliver_email + '</div>'
            + '<div class="o-info deliver_comment">Комментарий: ' + info.deliver_comment + '</div>'
            + '<hr>' + currentComponentsHtml + '<hr>'
            + '<button class="btn-accept-order" onclick="gl.functions.orders.courier.acceptOrder(' + info.id + ');return false;">Принять к исполнению</button>'
            + '<button onclick="gl.functions.orders.courier.declineOrder(' + info.id + ');return false;">Отказаться</button>'
            + '</div>';
        
        elems['#orders-pane'].prepend(html);
    }
    
    gl.functions.orders.courier.getActiveOrders = function() {
      $.get({$jsStrings['worker/get-active-orders']}, {worker_uid:{$jsStrings['worker_uid']},type:'courier'}, function(data) {
        if (data.status == 'success') {
            for (var id in data.orders) {
                var order = data.orders[id];
                var orderPane = elems['#orders-pane'].find('.order[data-id=' + order.id + ']');
                if (!orderPane.length) {
                    gl.functions.orders.courier.putNewOrderToPane(order);
                }
            }
        } else {
            //TODO: to translate
            gl.handleFailCustom('Unknown error');
        }
      }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
      });
    };
    
    gl.functions.orders.courier.getActiveOrders();
    /*setInterval(function() {
          gl.functions.orders.courier.getActiveOrders();
    }, 7000);*/
JS
);
?>
<div id="orders-pane"></div>
<div id="worker-place-map"></div>
