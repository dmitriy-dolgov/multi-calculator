<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\CoWorkerDeclineCauseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Co-Worker Decline Causes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-worker-decline-cause-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Co-Worker Decline Cause'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'co_worker_id',
            [
                'label' => Yii::t('app', 'Co-Worker'),
                'format' => 'raw',
                'value' => function (\common\models\db\CoWorkerDeclineCause $model) {
                    return $model->coWorker ? Html::encode($model->coWorker->name) : Yii::$app->formatter->nullDisplay;
                },
            ],
            [
                'label' => Yii::t('app', 'Co-worker function'),
                'format' => 'raw',
                'value' => function (\common\models\db\CoWorkerDeclineCause $model) {
                    /*$html = Yii::$app->formatter->nullDisplay;
                    if (!empty($model->coWorker->coWorkerFunctions)) {
                        $html = [];
                        foreach ($model->coWorker->coWorkerFunctions as $cwFunction) {
                            $html[] = Yii::t('db', $cwFunction->name);
                        }
                        $html = implode('<br>', $html);
                    }
                    return $html;*/
                    return $model->coWorker ? $model->coWorker->getCoWorkerFunctionsList() : Yii::$app->formatter->nullDisplay;
                },
            ],
            'cause:ntext',
            'order',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
