<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorker */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Co-workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$cwFuncValue = Yii::$app->formatter->nullDisplay;
if ($model->coWorkerFunctions) {
    $coWorkerFunctions = $model->coWorkerFunctions;
    array_walk($coWorkerFunctions, function (\common\models\db\CoWorkerFunction &$func) {
        $func = Html::encode(Yii::t('db', $func->name));
    });
    $cwFuncValue = implode('<br>', $coWorkerFunctions);
}

?>
<div class="co-worker-view">

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
            //'user_id',
            'name',
            [
                'label' => Yii::t('app', 'Co-worker functions'),
                'format' => 'raw',
                'value' => $cwFuncValue,    /*function (\common\models\db\CoWorker $model) {
                    return $model->coWorkerFunction ? Html::encode(Yii::t('app',
                        $model->coWorkerFunction->name)) : Yii::$app->formatter->nullDisplay;
                }*/
            ],
            'worker_site_uid',
            'birthday:date',
            'description:ntext',
        ],
    ]) ?>

</div>
