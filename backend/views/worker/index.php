<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $coWorkerFunctions \common\models\db\CoWorkerFunction[] */
/* @var $orders array */

$this->title = Yii::t('app', 'Co-worker main page');

$this->registerJs(<<<JS
    if (!gl.functions.orders) {
        gl.functions.orders = {};
    };
JS
);

/*$this->registerJsFile(Url::to(['/js/order-handling/longpoll.js']),
    ['depends' => ['backend\assets\WorkerAsset'], 'appendTimestamp' => YII_DEBUG]);*/

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

    <?php foreach ($orders as $id => $orderList): ?>
        <div class="function-orders-pane <?= $id ?>">
            <?php
            if ($id == 'accept_orders') {
                echo $this->render('_accept_orders', ['orderList' => $orderList, 'worker' => $worker]);
            } elseif ($id == 'maker') {
                echo $this->render('_cook', ['orderList' => $orderList, 'worker' => $worker]);
            } elseif ($id == 'courier') {
                echo $this->render('_courier', ['orderList' => $orderList, 'worker' => $worker]);
            }
            ?>
        </div>
    <?php endforeach; ?>

    <?php /*if (isset($coWorkerFunctions['accept_orders'])): */ ?><!--
        <hr>
        <? /*= $this->render('_accept_orders', ['worker' => $worker]) */ ?>
    <?php /*endif; */ ?>

    <?php /*if (isset($coWorkerFunctions['maker'])): */ ?>
        <hr>
        <? /*= $this->render('_cook', ['worker' => $worker]) */ ?>
    <?php /*endif; */ ?>

    <?php /*if (isset($coWorkerFunctions['courier'])): */ ?>
        <hr>
        <? /*= $this->render('_courier', ['worker' => $worker]) */ ?>
    --><?php /*endif; */ ?>
</main>
