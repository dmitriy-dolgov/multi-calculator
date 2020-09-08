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

/*$declineCausesHtml = '<select class="sel-decline-order-cause">'
    . '<option value="">Новая причина отказа</option>';
if ($worker->coWorkerDeclineCauses) {
    foreach ($worker->coWorkerDeclineCauses as $dCause) {
        $declineCausesHtml .= '<option value="' . $dCause->id . '">' . Html::encode($dCause->cause) . '</option>';
    }
}
$declineCausesHtml .= '</select><br>';

$declineCausesHtml = json_encode($declineCausesHtml);*/

$this->registerJsFile(Url::to(['/js/worker/order-handling.js']),
    ['depends' => ['backend\assets\WorkerAsset'], 'appendTimestamp' => YII_DEBUG]);
$this->registerJsFile(Url::to(['/js/order-handling/sse-backend.js', ['ver' => '1.0']]),
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
    
    gl.functions.orders.acceptOrders.acceptOrderByCourier = function(orderId) {
        $.post({$jsStrings['worker/accept-order-by-courier']}, {orderId:orderId,workerUid:{$jsStrings['workerUid']}}, function(data) {
          if (data.status == 'success') {
              alert('Запрос отправлен на выполнение.');
              
              //TODO: показывать уже существующие выполняющиеся заказы
              $('.function-orders-pane .order[data-id=' + orderId + '] .btn-accept-order-wrap').html(
                  '<i><b>Отправлен на выполнение.</b></i><br>'
                  + '<button class="btn btn-warning" onclick="gl.functions.orders.acceptOrders.acceptOrderByCourier(' + orderId + ');return false;">Передать курьеру</button>'
              );
          } else if (data.status == 'warning') {
              alert('Заказ отправлен курьеру но произошла ошибка: ' + data.msg);
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

    gl.functions.orders.acceptOrders.acceptOrder = function(orderId) {
        $.post({$jsStrings['worker/accept-order-by-maker']}, {orderId:orderId,workerUid:{$jsStrings['workerUid']}}, function(data) {
          if (data.status == 'success') {
              alert('Запрос отправлен на выполнение.');
              
              //TODO: показывать уже существующие выполняющиеся заказы
              $('.function-orders-pane .order[data-id=' + orderId + '] .btn-accept-order-wrap').html(
                  '<i><b>Отправлен на выполнение.</b></i><br>'
                  + '<button class="btn btn-warning" onclick="gl.functions.orders.acceptOrders.acceptOrderByCourier(' + orderId + ');return false;">Передать курьеру</button>'
              );
              /*$('.function-orders-pane .order[data-id=' + orderId + ']').fadeOut(400, function() {
                  // TODO: to remove
                  //this.remove();
              });*/
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
    
    gl.functions.orders.acceptOrders.declineOrder = function(id) {
        alert('Вы отклонили/отложили заказ.');
        elems['#orders-pane'].find('.order[data-id=' + id + ']').fadeOut(400, function() {
            // TODO: to remove
            //this.remove();
        });
    };
JS
);
?>
<?php foreach ($orderList as $orderData): ?>
    <?= $this->render('_order_element', ['worker' => $worker, 'orderData' => $orderData]) ?>
<?php endforeach; ?>
