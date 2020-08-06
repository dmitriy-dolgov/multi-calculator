<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */

$jsStrings = [
    'shop-order/get-active-orders' => Url::to(['shop-order/get-active-orders']),
    'worker_uid' => Yii::$app->request->get('worker_uid'),
];

$this->registerJs(<<<JS
    var elems = {
        '#orders-pane': $('#orders-pane')
    };

    gl.functions.acceptOrder = function(id) {
        $.post('shop-order/accept-order', {id:id}, function(data) {
          if (data.status == 'success') {
              alert('Заказ отправлен на выполнение.');
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
    
    gl.functions.declineOrder = function(id) {
        alert('В разработке');
    };

    //TODO: to translate
    function putNewOrderToPane(order) {
        var html = '<div class="order" data-id="' + order.id + '">'
            + '<div class="o-info order_uid">UID: ' + order.order_uid + '</div>'
            + '<div class="o-info created_at">Создан: ' + order.created_at + '</div>'
            + '<div class="o-info deliver_customer_name">Имя: ' + order.deliver_customer_name + '</div>'
            + '<div class="o-info deliver_address">Адрес: ' + order.deliver_address + '</div>'
            + '<div class="o-info deliver_phone">Телефон: ' + order.deliver_phone + '</div>'
            + '<div class="o-info deliver_email">Email: ' + order.deliver_email + '</div>'
            + '<div class="o-info deliver_comment">Комментарий: ' + order.deliver_comment + '</div>'
            + '<hr>'
            + '<button onlcick="gl.functions.acceptOrder(' + order.id + ')">Принять</button>'
            + '<button onlcick="gl.functions.declineOrder(' + order.id + ')">Отказаться</button>'
            + '</div>';
        
        elems['#orders-pane'].prepend(html);
    }

    setTimeout(function() {
          $.get({$jsStrings['shop-order/get-active-orders']}, {worker_uid:{$jsStrings['worker_uid']}}, function(data) {
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
        },
        7000
    );
JS
);
?>
<div id="orders-pane"></div>
