<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\CoWorkerFunction;
/* @var $this yii\web\View */
/* @var $searchModel common\models\db\CoWorkerFunctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Co-worker functions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-worker-function-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Co-worker function'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'name',
            [
                'label' => Yii::t('app', 'Name'),
                'value' => function (CoWorkerFunction $model) {
                    return $model->name ? Yii::t('db', $model->name) : Yii::$app->formatter->nullDisplay;
                },
            ],
            'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
