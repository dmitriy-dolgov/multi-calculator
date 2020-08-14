<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorkerDeclineCause */

$this->title = $model->cause;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Co-Worker Decline Causes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$coWorkerFunctionHtml = Yii::$app->formatter->nullDisplay;
if (!empty($model->coWorker->coWorkerFunctions)) {
    $coWorkerFunctionHtml = [];
    foreach ($model->coWorker->coWorkerFunctions as $cwFunction) {
        $coWorkerFunctionHtml[] = Yii::t('db', $cwFunction->name);
    }
    $coWorkerFunctionHtml = implode('<br>', $coWorkerFunctionHtml);
}

?>
<div class="co-worker-decline-cause-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'co_worker_id',
            [
                'label' => Yii::t('app', 'Co-Worker'),
                'format' => 'raw',
                'value' => $model->coWorker ? Html::encode($model->coWorker->name) : Yii::$app->formatter->nullDisplay,
            ],
            [
                'label' => Yii::t('app', 'Co-worker function'),
                'format' => 'raw',
                'value' => $coWorkerFunctionHtml,
            ],
            'cause:ntext',
            'order',
        ],
    ]) ?>

</div>
