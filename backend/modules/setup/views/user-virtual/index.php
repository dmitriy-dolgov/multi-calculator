<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\UserVirtual;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\UserVirtualSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Виртуальный продавец');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-virtual-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Создать виртуального продавца'), ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'label' => Yii::t('app', 'Данные владельца'),
                'value' => function (UserVirtual $model) {
                    if ($model->user) {
                        return "{$model->user->username} ({$model->user->email})";
                    }

                    return Yii::$app->formatter->nullDisplay;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
