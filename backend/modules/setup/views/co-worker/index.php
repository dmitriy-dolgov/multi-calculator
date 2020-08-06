<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\CoWorkerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Co-workers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-worker-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Co-worker'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',
            'name',
            [
                'label' => Yii::t('app', 'Co-worker functions'),
                'format' => 'raw',
                'value' => function (\common\models\db\CoWorker $model) {
                    $result = Yii::$app->formatter->nullDisplay;
                    if ($model->coWorkerFunctions) {
                        $coWorkerFunctions = $model->coWorkerFunctions;
                        array_walk($coWorkerFunctions, function (\common\models\db\CoWorkerFunction &$func) {
                            $func = Html::encode(Yii::t('db', $func->name));
                        });
                        $result = implode('<br>', $coWorkerFunctions);
                    }
                    return $result;
                }
            ],
            'worker_site_uid',
            'birthday:date',
            'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
