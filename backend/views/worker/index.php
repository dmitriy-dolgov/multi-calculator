<?php

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $coWorkerFunctions \common\models\db\CoWorkerFunction[] */
/* @var $orders array */

$this->title = Yii::t('app', 'Co-worker main page');

//$coWorkerFunctions = \yii\helpers\ArrayHelper::map($worker->coWorkerFunctions, 'id', 'name');

$this->registerJsFile(Url::to(['/js/order-handling/websocket.js']), ['depends' => ['backend\assets\WorkerAsset'], 'appendTimestamp' => YII_DEBUG]);

$userSocketId = json_encode($worker->user_id + 1);

$this->registerJs(<<<JS
    if (gl.functions.orders) {
        alert('"gl.functions.orders" already set');
    }
    gl.functions.orders = {};

    if (!gl.data) {
        gl.data = {};
    }
    
    gl.data.user_socket_id = $userSocketId;
JS
);

?>
<div class="co-worker-page">
    <h1><?= Yii::t('app', 'Individual Co-worker`s site.') ?></h1>
    <h3><?= Yii::t('app', 'Your name: {name}', ['name' => $worker->name]) ?></h3>
    <hr>
    <h4><?= Yii::t('app', 'Your tasks:') ?></h4>
    <?php if ($coWorkerFunctions): ?>
        <ul>
            <?php foreach ($coWorkerFunctions as $cwFunction): ?>
                <li><?= Yii::t('db', $cwFunction) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <?= Yii::t('app', 'No tasks. Please refer to your manager.') ?>
    <?php endif; ?>

    <?php if (isset($coWorkerFunctions['accept_orders'])): ?>
        <hr>
        <?= $this->render('_accept_orders', ['worker' => $worker]) ?>
    <?php endif; ?>

    <?php if (isset($coWorkerFunctions['cook'])): ?>
        <hr>
        <?= $this->render('_cook', ['worker' => $worker]) ?>
    <?php endif; ?>

    <?php if (isset($coWorkerFunctions['courier'])): ?>
        <hr>
        <?= $this->render('_courier', ['worker' => $worker]) ?>
    <?php endif; ?>
</div>
