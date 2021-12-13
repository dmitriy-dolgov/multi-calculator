<?php

use api\modules\admin\widgets\shopOrders\ShopOrders;
use common\models\db\User;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var $this         yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel  Da\User\Search\UserSearch
 */

$this->title = Yii::t('app', 'List of orders by users.');
$this->params['breadcrumbs'][] = $this->title;

/**
 * TODO: правильно что закомментил?
 * $this->registerJs(<<<JS
gl.functions.unwrapOrderInfo = function(elem) {
    gl.log('unwrapOrderInfo');
    $(elem).parent().find('.order-fold').toggle('fold');
}
JS
);*/

?>
<?php Pjax::begin() ?>
<div class="table-responsive">
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    //'detailUrl' => \yii\helpers\Url::to(['/admin/order/orders-by-user']),
                    'detail' => function(User $userModel) {
                        return ShopOrders::widget(['modelUser' => User::getOrFail($userModel->id)]);
                    },
                    'expandTitle' => Yii::t('app', 'Expand orders'),
                    'expandAllTitle' => Yii::t('app', 'Expand all orders'),
                    'collapseTitle' => Yii::t('app', 'Collapse orders'),
                    'collapseAllTitle' => Yii::t('app', 'Collapse all orders'),
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'expandOneOnly' => true,
                ],
                'username',
                'email:email',
            ],
        ]
    ); ?>
</div>
<?php Pjax::end() ?>
