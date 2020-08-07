<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */

$jsStrings = [
    'worker/get-active-orders' => json_encode(Url::to(['worker/get-active-orders'])),
    'worker/accept-order' => json_encode(Url::to(['worker/accept-order'])),
    'worker_uid' => json_encode(Yii::$app->request->get('worker_uid')),
];

$this->registerCss(<<<CSS
.order {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #243444;
}
CSS
);

$this->registerJs(<<<JS
    var elems = {
        '#orders-pane': $('#orders-pane')
    };

    if (gl.functions.orders.acceptOrders) {
        alert('"gl.functions.orders.acceptOrders" already set');
    }
    gl.functions.orders.acceptOrders = {};

    gl.functions.orders.acceptOrders.acceptOrder = function(id) {
        $.post({$jsStrings['worker/accept-order']}, {id:id}, function(data) {
          if (data.status == 'success') {
              alert('Отправлен запрос исполнителю на подтверждение.');
              elems['#orders-pane'].find('.order[data-id=' + id + ']').fadeOut(400, function() {
                  // TODO: to remove
                  //this.remove();
              });
          } else {
              //TODO: to translate , maybe handle error
                gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
    
    gl.functions.orders.acceptOrders.declineOrder = function(id) {
        alert('В разработке');
    };

    //TODO: to translate
    gl.functions.orders.acceptOrders.putNewOrderToPane = function(order) {
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
            + '<button onclick="gl.functions.orders.acceptOrders.acceptOrder(' + info.id + ')">Отослать приглашение исполнителю</button>'
            + '<button onclick="gl.functions.orders.acceptOrders.declineOrder(' + info.id + ')">Отложить</button>'
            + '</div>';
        
        elems['#orders-pane'].prepend(html);
    }
    
    gl.functions.orders.acceptOrders.getActiveOrders = function() {
      $.get({$jsStrings['worker/get-active-orders']}, {worker_uid:{$jsStrings['worker_uid']}}, function(data) {
        if (data.status == 'success') {
            for (var id in data.orders) {
                var order = data.orders[id];
                var orderPane = elems['#orders-pane'].find('.order[data-id=' + order.id + ']');
                //TODO: здесь проверять не сменился ли статус заказа и обновлять панель
                if (!orderPane.length) {
                    gl.functions.orders.acceptOrders.putNewOrderToPane(order);
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
    
    gl.functions.orders.acceptOrders.getActiveOrders();
    /*setInterval(function() {
          gl.functions.orders.acceptOrders.getActiveOrders();
    }, 7000);*/
JS
);
?>
<div id="orders-pane"></div>
