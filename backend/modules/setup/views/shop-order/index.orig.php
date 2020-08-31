<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\ShopOrder;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\ShopOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Order List');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.make-order-index td hr {
    margin: 7px 0;
    border-top: 1px solid #848484;
}
.amount-caption {
    color: black;
}
CSS
);

?>
<div class="make-order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--<p>
        <?php /*= Html::a(Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>-->

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',
            'order_uid',
            'created_at',
            'deliver_customer_name',
            'deliver_address:ntext',
            'deliver_phone',
            'deliver_email:email',
            'deliver_comment:ntext',
            //'deliver_required_time_begin',
            //'deliver_required_time_end',
            [
                'label' => Yii::t('app', 'Components'),
                'format' => 'html',
                'value' => function (ShopOrder $model) {
                    $compList = [];
                    foreach ($model->shopOrderComponents as $component) {
                        $compList[] = Html::a($component->name,
                            ['/setup/component/view', 'id' => $component->component->id],
                            ['target' => '_blank']);
                    }
                    return implode('<hr>', $compList);
                },
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'format' => 'html',
                'value' => function (ShopOrder $model) {
                    $usersText = '<div class="amount-caption">'
                        . Yii::t('app', 'Total pizzerias: {amount}', ['amount' => $model->getAmountOfUsers()])
                        . '</div>';

                    $statusList = [];
                    foreach ($model->shopOrderStatuses as $status) {
                        $statusList[] = $status->getStatusName();
                    }
                    return $usersText . '<hr>' . implode('<hr>', $statusList);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
//                'template' => '{view}{update}',
//                'buttons' => [
//                    'update' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
//                            'title' => Yii::t('app', 'Update'),
//                            'data-confirm' => Yii::t('app', 'Are you sure you want to update this record?'),
//                        ]);
//                    },
//                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
