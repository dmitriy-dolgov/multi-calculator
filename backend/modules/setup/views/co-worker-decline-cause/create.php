<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorkerDeclineCause */
/* @var $coWorkers common\models\db\CoWorker[] */

$this->title = Yii::t('app', 'Create Co-Worker Decline Cause');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Co-Worker Decline Causes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-worker-decline-cause-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'coWorkers' => $coWorkers,
    ]) ?>

</div>
