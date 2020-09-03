<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */

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

    if (gl.functions.orders.maker) {
        alert('"gl.functions.orders.maker" already set');
    }
    gl.functions.orders.maker = {};
    
    gl.functions.orders.maker.passOrderToCourier = function(id) {
        $.post('worker/pass-order-to-courier', {id:id,type:'maker'}, function(data) {
            alert('Заказ отправлен курьеру!');
            elems['#orders-pane'].find('.order[data-id=' + id + ']').fadeOut(400, function() {
              // TODO: to remove
              //this.remove();
            });
        });
    };

    gl.functions.orders.maker.acceptOrder = function(id) {
        $.post('worker/accept-order', {id:id,type:'maker'}, function(data) {
          if (data.status == 'success') {
              alert('Вы перешли в статус изготовления пиццы.');
              /*elems['#orders-pane'].find('.order[data-id=' + id + ']').fadeOut(400, function() {
                  // TODO: to remove
                  //this.remove();
              });*/
              var html = 'Заказ исполняется вами! &nbsp;<button onclick="gl.functions.orders.maker.passOrderToCourier(' + id + ');return false;">Передать курьеру</button>';
              elems['#orders-pane'].find('.order[data-id=' + id + ']').find('.btn-accept-order').replaceWith(html);
          } else {
              //TODO: to translate , maybe handle error
                gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
    
    gl.functions.orders.maker.declineOrder = function(id) {
        alert('В разработке');
    };

    //TODO: to translate
    gl.functions.orders.maker.putNewOrderToPane = function(order) {
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
            + '<button class="btn-accept-order" onclick="gl.functions.orders.maker.acceptOrder(' + info.id + ');return false;">Принять к исполнению</button>'
            + '<button onclick="gl.functions.orders.maker.declineOrder(' + info.id + ');return false;">Отказаться</button>'
            + '</div>';
        
        elems['#orders-pane'].prepend(html);
    }
    
    gl.functions.orders.maker.getActiveOrders = function() {
      $.get({$jsStrings['worker/get-active-orders']}, {worker_uid:{$jsStrings['worker_uid']},type:'maker'}, function(data) {
        if (data.status == 'success') {
            for (var id in data.orders) {
                var order = data.orders[id];
                var orderPane = elems['#orders-pane'].find('.order[data-id=' + order.id + ']');
                if (!orderPane.length) {
                    gl.functions.orders.maker.putNewOrderToPane(order);
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
    
    gl.functions.orders.maker.getActiveOrders();
    /*setInterval(function() {
          gl.functions.orders.maker.getActiveOrders();
    }, 7000);*/
JS
);
?>
<div id="orders-pane"></div>
