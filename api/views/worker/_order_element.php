<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $orderData array */

?>
<div class="order" data-id="<?= $orderData['info']['id'] ?>">

    <table class="order-properties">
        <tr>
            <td class="name">ID:</td>
            <td class="value"><?= $orderData['info']['id'] ?></td>
        </tr>
        <tr>
            <td class="name">UID:</td>
            <td class="value"><?= $orderData['info']['order_uid'] ?></td>
        </tr>
        <tr>
            <td class="name">Создан:</td>
            <td class="value"><?= $orderData['info']['created_at'] ?></td>
        </tr>
        <tr>
            <td class="name">Прошло:</td>
            <td class="value time-passed" data-uid="<?= $orderData['info']['order_uid'] ?>"></td>
        </tr>
    </table>

    <script>
        gl.functions.startTimerOut(<?= json_encode($orderData['info']['order_uid']) ?>, <?= json_encode($orderData['info']['created_at']) ?>);
    </script>

    <?php /* ?>
    <div class="o-info id">ID: <?= $orderData['info']['id'] ?></div>
    <div class="o-info order_uid">UID: <?= Html::encode($orderData['info']['order_uid']) ?></div>
    <div class="o-info created_at">Создан: <?= Html::encode($orderData['info']['created_at']) ?></div>
    <?php */ ?>

    <div class="panel-group" id="accordion<?= $orderData['info']['id'] ?>" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading<?= $orderData['info']['id'] ?>">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse"
                       data-parent="#accordion<?= $orderData['info']['id'] ?>"
                       href="#collapse<?= $orderData['info']['id'] ?>" aria-expanded="false"
                       aria-controls="collapse<?= $orderData['info']['id'] ?>">
                        Заказ <?= $orderData['info']['id'] ?>
                    </a>
                </h4>
            </div>
            <div id="collapse<?= $orderData['info']['id'] ?>" class="panel-collapse collapse" role="tabpanel"
                 aria-labelledby="heading<?= $orderData['info']['id'] ?>">
                <div class="panel-body">
                    <div class="to-unwrap">
                        <div class="o-info deliver_customer_name">
                            Имя: <?= Html::encode($orderData['info']['deliver_customer_name']) ?></div>
                        <div class="o-info deliver_address">
                            Адрес: <?= Html::encode($orderData['info']['deliver_address']) ?></div>
                        <div class="o-info deliver_phone">
                            Телефон: <?= Html::encode($orderData['info']['deliver_phone']) ?></div>
                        <div class="o-info deliver_email">
                            Email: <?= Html::encode($orderData['info']['deliver_email']) ?></div>
                        <div class="o-info deliver_comment">
                            Комментарий: <?= Html::encode($orderData['info']['deliver_comment']) ?></div>
                    </div>

                    <hr>
                    Компоненты:<br>
                    <?php foreach ($orderData['components'] as $comp): ?>
                        <i><?= Html::encode($comp['on_current']['name'] ?? 'Без имени') ?></i> Цена: <?= Html::encode($comp['on_current']['price'] ?? Yii::t('app',
                            'For free')) ?> р.
                        <?php if (($comp['on_deal']['amount'] ?? 1) > 1): ?>
                            (<?= $comp['on_deal']['amount'] ?> шт.)
                        <?php endif; ?>
                        <br>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="btn-accept-order-wrap">
        <?php if ($orderData['status'] == 'created'): ?>
            <button class="btn btn-primary"
                    onclick="gl.functions.orders.acceptOrders.acceptOrder(<?= $orderData['info']['id'] ?>);return false;">
                Передать исполнителю
            </button>
        <?php elseif ($orderData['status'] == 'accepted-by-maker'): ?>
            <i><b>Отправлен на выполнение.</b></i><br>
            <button class="btn btn-warning"
                    onclick="gl.functions.orders.acceptOrders.acceptOrderByCourier(<?= $orderData['info']['id'] ?>);return false;">
                Передать курьеру
            </button>
        <?php elseif ($orderData['status'] == 'accepted-by-courier'): ?>
            <i><b>Передан курьеру.</b></i><br>
            <button class="btn btn-warning"
                    onclick="gl.functions.orders.acceptOrders.signalTheUser('courier-arrived', <?= $orderData['info']['id'] ?>);return false;">
                Сигнал покупателю о прибытии курьера
            </button>
            <button class="btn btn-danger"
                    onclick="gl.functions.orders.acceptOrders.completeOrder(<?= $orderData['info']['id'] ?>);return false;">
                Завершить заказ
            </button>
        <?php elseif ($orderData['status'] == 'finished'): ?>
            <i><b>Заказ завершен.</b></i>
        <?php else: ?>
            <?= Yii::t('app', 'Unknown order status "{status}". Please refer your manager.',
                ['status' => $orderData['status']]) ?>
        <?php endif; ?>
    </div>
    <?php if ($orderData['status'] != 'finished'): ?>
        <hr>
        <div class="decline-order-panel">
            <button class="btn btn-danger btn-decline-order" style="margin-bottom:10px; width:100%"
                    onclick="gl.functions.orders.acceptOrders.declineOrder(<?= $orderData['info']['id'] ?>);return false;">
                Отложить/отказаться
            </button>
            <br>
            <select class="sel-decline-order-cause" style="margin-bottom:10px; width:100%">
                <option value="">Новая причина отказа</option>
                <?php
                //TODO: при появлении нового заказа этого поля не будет - исправить
                if ($worker && $worker->coWorkerDeclineCauses) {
                    foreach ($worker->coWorkerDeclineCauses as $dCause) {
                        echo '<option value="' . $dCause->id . '">' . Html::encode($dCause->cause) . '</option>';
                    }
                }
                ?>
            </select>
            <br>
            <textarea placeholder="Новая причина отказа" class="text-new-decline-order-cause"
                      style="width:100%"></textarea>
        </div>
    <?php endif; ?>
    <hr>
</div>
