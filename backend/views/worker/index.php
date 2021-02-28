<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $coWorkerFunctions \common\models\db\CoWorkerFunction[] */
/* @var $orders array */

$this->title = Yii::t('app', 'Co-worker main page');

$this->registerJs(<<<JS
    if (!window.gl) {
        window.gl = {};
    }
    if (!gl.functions) {
        gl.functions = {};
    }
    
    if (!gl.functions.orders) {
        gl.functions.orders = {};
    }
    if (!gl.functions.startTimerOut) {
        gl.functions.startTimerOut = function (orderUid, createdAt) {
            console.log(orderUid + ' > ' + createdAt);
        };
    }
    
JS
    , \yii\web\View::POS_HEAD
);

$this->registerCss(<<<CSS
.order-properties {
    width: 100%;
}
.btn-accept-order-wrap {
    width: 100%;
}
.btn-accept-order-wrap button {
    width: 100%;
    margin-top: 10px;
}
.order-properties .value {
    text-align: right;
    font-weight: bold;
}
CSS
);

?>
<header class="header">
    <div class="caption"><?= Html::encode(Yii::t('app', 'Individual Co-Worker`s Site')) ?></div>
    <div class="info">
        <div><?= Html::encode($worker->name) ?></div>
        <ul class="function-list">
            <?php if ($coWorkerFunctions): ?>
                <?php foreach ($coWorkerFunctions as $id => $name): ?>
                    <li class="cw-function <?= $id ?>"><?= Html::encode(Yii::t('db', $name)) ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <?= Yii::t('app', 'No tasks. Please refer to your manager.') ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>

<main class="body">

    <?= \common\widgets\Alert::widget() ?>

    <?php foreach ($orders as $workerType => $orderList): ?>
        <div class="function-orders-pane <?= $workerType ?>">
            <?php
            if ($workerType == 'accept_orders') {
                echo $this->render('_accept_orders', ['orderList' => $orderList, 'worker' => $worker]);
            } elseif ($workerType == 'maker') {
                echo $this->render('_maker', ['orderList' => $orderList, 'worker' => $worker]);
            } elseif ($workerType == 'courier') {
                echo $this->render('_courier', ['orderList' => $orderList, 'worker' => $worker]);
            }
            ?>
        </div>
    <?php endforeach; ?>
</main>
