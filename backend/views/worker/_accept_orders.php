<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $orderList array */

$jsStrings = [
    //'worker/get-active-orders' => json_encode(Url::to(['worker/get-active-orders'])),
    'worker/accept-order-by-maker' => json_encode(Url::to(['worker/accept-order-by-maker'])),
    'worker/accept-order-by-courier' => json_encode(Url::to(['worker/accept-order-by-courier'])),
    'workerUid' => json_encode(Yii::$app->request->get('workerUid')),
    'worker/decline-order' => json_encode(Url::to(['worker/decline-order'])),
];

$this->registerJsFile(Url::to(['/js/worker/order-handling.js']),
    ['depends' => ['backend\assets\WorkerAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/order-handling/sse-backend.js', 'ver' => '1.1']),
    ['depends' => ['backend\assets\WorkerAsset'], 'appendTimestamp' => YII_DEBUG]);


$this->registerJs(<<<JS
    var elems = {
        '#orders-pane': $('#orders-pane')
    };

    if (gl.functions.orders.acceptOrders) {
        alert('"gl.functions.orders.acceptOrders" already set');
    }
    
    gl.functions.orders.acceptOrders = {};
   
    gl.functions.orders.acceptOrders.sendDeclineRequestReadyMessage = function(declineRequestId) {
        $.post({$jsStrings['worker/decline-order']}, {declineRequestId:declineRequestId}, function(data) {
            if (data.status == 'success') {
                
            } else {
                alert('Unknown error!');
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
    
    //TODO: orderId => orderUid
    gl.functions.orders.acceptOrders.acceptOrderByCourier = function(orderId) {
        $.post({$jsStrings['worker/accept-order-by-courier']}, {orderId:orderId,workerUid:{$jsStrings['workerUid']}}, function(data) {
          if (data.status == 'success') {
              alert('Заказ передан курьеру.');
              
              $('.function-orders-pane .order[data-id=' + orderId + ']').replaceWith(data.order_html);
          } else if (data.status == 'warning-custom') {
              alert(data.msg);
          } else {
              //TODO: to translate , maybe handle error
              gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };

    //TODO: orderId => orderUid
    gl.functions.orders.acceptOrders.acceptOrder = function(orderId) {
        $.post({$jsStrings['worker/accept-order-by-maker']}, {orderId:orderId,workerUid:{$jsStrings['workerUid']}}, function(data) {
          if (data.status == 'success') {
              alert('Заказ отправлен на выполнение поварам.');
              
              $('.function-orders-pane .order[data-id=' + orderId + ']').replaceWith(data.order_html);
          } /*else if (data.status == 'warning') {
              alert('Заказ отправлен на выполнение но произошла ошибка: ' + data.msg);
          }*/ else if (data.status == 'warning-custom') {
              alert(data.msg);
          } else {
              //TODO: to translate , maybe handle error
              gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
    
    gl.functions.orders.acceptOrders.declineOrder = function(orderId) {
        alert('Вы отклонили/отложили заказ.');
        elems['#orders-pane'].find('.order[data-id=' + orderId + ']').fadeOut(400, function() {
            // TODO: to remove
            //this.remove();
        });
    };
    
    gl.functions.orders.acceptOrders.completeOrder = function(orderId) {
      if (!confirm('Вы уверены что хотите завершить заказ?')) {
          return false;
      }
      
      $.post('worker/complete-order', {orderId:orderId,workerUid:{$jsStrings['workerUid']}}, function(data) {
          if (data.status == 'success') {
              alert('Заказ завершен.');
              
              $('.function-orders-pane .order[data-id=' + orderId + ']').replaceWith(data.order_html);
          } else if (data.status == 'warning-custom') {
              alert(data.msg);
          } else {
              //TODO: to translate , maybe handle error
              gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
    
    gl.functions.orders.acceptOrders.signalTheUser = function(type, orderId) {
        $.post('worker/' + type, {orderId:orderId,workerUid:{$jsStrings['workerUid']}}, function(data) {
          if (data.status == 'success') {
              //TODO: как и везде, выдавать только после подтверждения со стороны пользователя
              alert('Сигнал отправлен пользователю.');
              
          } else if (data.status == 'warning-custom') {
              alert(data.msg);
          } else {
              //TODO: to translate , maybe handle error
              gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
JS
);
?>
<?php foreach ($orderList as $key => $orderData): ?>
    <?= $this->render('_order_element', ['worker' => $worker, 'orderData' => $orderData]) ?>
    <?php if ($key > 10) {
        break;
    } ?>
<?php endforeach; ?>
