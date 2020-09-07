<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $ord array */

?>
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
        <i><?= Html::encode($comp['on_current']['name'] ?? 'Без имени') ?></i> Цена: <?= Html::encode($comp['on_current']['price'] ?? Yii::t('app', 'For free')) ?> р.
        <?php if (($comp['on_deal']['amount'] ?? 1) > 1): ?>
            (<?= $comp['on_deal']['amount'] ?> шт.)
        <?php endif; ?>
        <br>
    <?php endforeach; ?>
    <hr>
    <div class="btn-accept-order-wrap">
        <button class="btn btn-warning"
                onclick="gl.functions.orders.acceptOrders.acceptOrder(<?= $ord['info']['id'] ?>);return false;">
            Передать исполнителю
        </button>
    </div>
    <hr>
    <div class="decline-order-panel">
        <button class="btn btn-decline-order"
                onclick="gl.functions.orders.acceptOrders.declineOrder(<?= $ord['info']['id'] ?>);return false;">
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
