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
    margin-bottom: 20px;
}
CSS
);

$this->registerJs(<<<JS
    var elems = {
        '#orders-pane': $('#orders-pane')
    };

    gl.functions.acceptOrders = {};

    gl.functions.acceptOrders.acceptOrder = function(id) {
        $.post({$jsStrings['worker/accept-order']}, {id:id}, function(data) {
          if (data.status == 'success') {
              alert('Отправлен запрос пользователю на подтверждение.');
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
    
    gl.functions.acceptOrders.declineOrder = function(id) {
        alert('В разработке');
    };

    //TODO: to translate
    function putNewOrderToPane(order) {
        var html = '<div class="order" data-id="' + order.id + '">'
            + '<div class="o-info id">ID: ' + order.id + '</div>'
            + '<div class="o-info order_uid">UID: ' + order.order_uid + '</div>'
            + '<div class="o-info created_at">Создан: ' + order.created_at + '</div>'
            + '<div class="o-info deliver_customer_name">Имя: ' + order.deliver_customer_name + '</div>'
            + '<div class="o-info deliver_address">Адрес: ' + order.deliver_address + '</div>'
            + '<div class="o-info deliver_phone">Телефон: ' + order.deliver_phone + '</div>'
            + '<div class="o-info deliver_email">Email: ' + order.deliver_email + '</div>'
            + '<div class="o-info deliver_comment">Комментарий: ' + order.deliver_comment + '</div>'
            + '<hr>'
            + '<button onclick="gl.functions.acceptOrders.acceptOrder(' + order.id + ')">Отослать приглашение пользователю</button>'
            + '<button onclick="gl.functions.acceptOrders.declineOrder(' + order.id + ')">Отложить</button>'
            + '</div>';
        
        elems['#orders-pane'].prepend(html);
    }
    
    gl.functions.acceptOrders.getActiveOrders = function() {
      $.get({$jsStrings['worker/get-active-orders']}, {worker_uid:{$jsStrings['worker_uid']}}, function(data) {
        if (data.status == 'success') {
            for (var id in data.orders) {
                var order = data.orders[id];
                var orderPane = elems['#orders-pane'].find('.order[data-id=' + order.id + ']');
                if (!orderPane.length) {
                    putNewOrderToPane(order);
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
    
    gl.functions.acceptOrders.getActiveOrders();
    setInterval(function() {
          gl.functions.acceptOrders.getActiveOrders();
    }, 7000);
JS
);
?>
<div id="orders-pane"></div>
