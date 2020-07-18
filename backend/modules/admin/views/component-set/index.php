<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\db\ComponentSetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Component Sets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="component-set-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="text-warning">
        <?= Yii::t('app', 'Наборы шаблонов компонентов, доступны продавцу продукта для копирования в свой список компонентов.') ?>
    </div>

    <br>

    <p>
        <?= Html::a(Yii::t('app', 'Create Component Set'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
