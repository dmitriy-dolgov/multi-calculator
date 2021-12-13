<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorkerDeclineCause */
/* @var $coWorkers common\models\db\CoWorker[] */

$coWorkerName = $model->coWorker ? $model->coWorker->name : Yii::t('yii', '(not set)');   //Yii::$app->formatter->nullDisplay;

$this->title = Yii::t('app', 'Update Co-Worker Decline Cause: {name}', [
    'name' => $coWorkerName,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Co-Worker Decline Causes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $coWorkerName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="co-worker-decline-cause-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'coWorkers' => $coWorkers,
    ]) ?>

</div>
