<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\CustomerActiveComponent;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\CustomerActiveComponentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Chosen Components');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-active-component-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Add component from list'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'component_id',
            [
                'label' => Yii::t('app', 'Component'),
                'format' => 'raw',
                'value' => function (CustomerActiveComponent $model) {
                    return $model->getComponentInfoHtml();
                },
            ],
            'price_override',
            'price_discount_override',
            //'amount',
            //'unit_id',
            //'unit_value',
            //'unit_value_min',
            //'unit_value_max',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
