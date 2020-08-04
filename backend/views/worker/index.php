<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */

$this->title = Yii::t('app', 'Co-worker main page');

?>
<div class="co-worker-page">
    <h1><?= Yii::t('app', 'Individual Co-worker`s site.') ?></h1>
    <h3><?= Yii::t('app', 'Your name: {name}', ['name' => $worker->name]) ?></h3>
</div>
