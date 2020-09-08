<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $orderList array */

$jsStrings = [
    'worker/accept-order-by-courier' => json_encode(Url::to(['worker/accept-order-by-courier'])),
    'worker_uid' => json_encode(Yii::$app->request->get('worker_uid')),
    'worker/decline-order' => json_encode(Url::to(['worker/decline-order'])),
];

$this->registerJs(<<<JS
    var elems = {
        '#orders-pane': $('#orders-pane')
    };

    if (gl.functions.orders.maker) {
        alert('"gl.functions.orders.maker" already set');
    }
    
    gl.functions.orders.maker = {};
   
    gl.functions.orders.maker.sendDeclineRequestReadyMessage = function(declineRequestId) {
        $.post({$jsStrings['worker/decline-order']}, {declineRequestId:declineRequestId}, function(data) {
            if (data.status == 'success') {
                
            } else {
                alert('Unknown error!');
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };

    gl.functions.orders.maker.acceptOrder = function(id) {
        $.post({$jsStrings['worker/accept-order-by-courier']}, {id:id,worker_uid:{$jsStrings['worker_uid']}}, function(data) {
          if (data.status == 'success') {
              alert('Готовый продукт передан курьеру.');
              
              //TODO: показывать уже существующие выполняющиеся заказы
              $('.function-orders-pane .order[data-id=' + id + '] .btn-accept-order-wrap').html('<i><b>Продукт передан курьеру.</b></i>');
              /*$('.function-orders-pane .order[data-id=' + id + ']').fadeOut(400, function() {
                  // TODO: to remove
                  //this.remove();
              });*/
          } else if (data.status == 'warning') {
              alert('Готовый продукт передан курьеру но произошла ошибка: ' + data.msg);
          } else {
              //TODO: to translate , maybe handle error
                gl.handleFailCustom('Unknown error');
          }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
        });
    };
    
    gl.functions.orders.maker.declineOrder = function(id) {
        alert('Вы отклонили/отложили заказ.');
        elems['#orders-pane'].find('.order[data-id=' + id + ']').fadeOut(400, function() {
            // TODO: to remove
            //this.remove();
        });
    };
JS
);
?>
<?php foreach ($orderList as $ord): ?>
    <div class="order" data-id="<?= $ord['info']['id'] ?>">
        <div class="o-info id">ID: <?= $ord['info']['id'] ?></div>
        <div class="o-info order_uid">UID: <?= Html::encode($ord['info']['order_uid']) ?></div>
        <div class="o-info created_at">Создан: <?= Html::encode($ord['info']['created_at']) ?></div>
        <div class="o-info deliver_customer_name">
            Имя: <?= Html::encode($ord['info']['deliver_customer_name']) ?></div>
        <div class="o-info deliver_address">
            Адрес: <?= Html::encode($ord['info']['deliver_address']) ?></div>
        <div class="o-info deliver_phone">Телефон: <?= Html::encode($ord['info']['deliver_phone']) ?></div>
        <div class="o-info deliver_email">Email: <?= Html::encode($ord['info']['deliver_email']) ?></div>
        <div class="o-info deliver_comment">
            Комментарий: <?= Html::encode($ord['info']['deliver_comment']) ?></div>
        <hr>
        Компоненты:<br>
        <?php foreach ($ord['components'] as $comp): ?>
            <i><?= Html::encode($comp['on_current']['name']) ?></i> Цена: <?= Html::encode($comp['on_current']['price']) ?> р.
            <?php if ($comp['on_deal']['amount'] > 1): ?>
                (<?= $comp['on_deal']['amount'] ?> шт.)
            <?php endif; ?>
            <br>
        <?php endforeach; ?>
        <hr>
        <div class="btn-accept-order-wrap">
            <button class="btn btn-warning"
                    onclick="gl.functions.orders.maker.acceptOrder(<?= $ord['info']['id'] ?>);return false;">
                Передать курьеру
            </button>
        </div>
        <hr>
        <div class="decline-order-panel">
            <button class="btn btn-decline-order"
                    onclick="gl.functions.orders.maker.declineOrder(<?= $ord['info']['id'] ?>);return false;">
                Отложить/отказаться
            </button>
            <select class="sel-decline-order-cause">
                <option value="">Новая причина отказа</option>
                <?php
                if ($worker->coWorkerDeclineCauses) {
                    foreach ($worker->coWorkerDeclineCauses as $dCause) {
                        echo '<option value="' . $dCause->id . '">' . Html::encode($dCause->cause) . '</option>';
                    }
                }
                ?>
            </select>
            <br>
            Новая причина отказа:<br><textarea class="text-new-decline-order-cause"></textarea>
        </div>
    </div>
    <hr>
    <hr>
<?php endforeach; ?>
