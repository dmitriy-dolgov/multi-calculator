<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */

$this->title = Yii::t('app', 'Co-worker main page');

$coWorkerFunctions = \yii\helpers\ArrayHelper::map($worker->coWorkerFunctions, 'id', 'name');

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

    <?php if (isset($coWorkerFunctions['courier'])): ?>
        <hr>
        <?= $this->render('_courier', ['worker' => $worker]) ?>
    <?php endif; ?>

    <?php if (isset($coWorkerFunctions['accept_orders'])): ?>
        <hr>
        <?= $this->render('_accept_orders', ['worker' => $worker]) ?>
    <?php endif; ?>
</div>