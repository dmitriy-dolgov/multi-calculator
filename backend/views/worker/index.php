<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $coWorkerFunctions \common\models\db\CoWorkerFunction[] */
/* @var $orders array */

$this->title = Yii::t('app', 'Co-worker main page');

//$coWorkerFunctions = \yii\helpers\ArrayHelper::map($worker->coWorkerFunctions, 'id', 'name');

/*$this->registerJsFile(Url::to(['/js/order-handling/websocket.js']),
    ['depends' => ['backend\assets\WorkerAsset'], 'appendTimestamp' => YII_DEBUG]);

$userSocketId = json_encode($worker->user_id + 1);
$yiiParams = json_encode(Yii::$app->params['websocket']);

$this->registerJs(<<<JS
    if (!window.gl) {
        var gl = {};
    }
    
    // For global functions
    if (!gl.functions) {
        gl.functions = {};
    }
    
    // For global data
    if (!gl.data) {
        gl.data = {};
    }

    if (gl.functions.orders) {
        alert('"gl.functions.orders" already set');
    }
    gl.functions.orders = {};
    
    gl.data.user_socket_id = $userSocketId;
    gl.data.params = {
        websocket: $yiiParams
    };
JS
, \yii\web\View::POS_HEAD);*/

$this->registerJs(<<<JS
    if (!gl.functions.orders) {
        gl.functions.orders = {};
    };
JS
);

$this->registerJsFile(Url::to(['/js/order-handling/longpoll.js']),
    ['depends' => ['backend\assets\WorkerAsset'], 'appendTimestamp' => YII_DEBUG]);

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
