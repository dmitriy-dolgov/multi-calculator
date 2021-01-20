<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\User;
use common\models\db\ShopOrder;

/**
 * @var $this         yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel  Da\User\Search\UserSearch
 * @var $module       Da\User\Module
 */

$this->title = Yii::t('usuario', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;

$module = Yii::$app->getModule('user');

$this->registerJs(<<<JS
$('.btn-order-info').click(function() {
  $(this).parent().find('.order-fold').toggle('fold');
});

JS
);

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
                    'detail' => function (User $modelUser, $key, $index) {

                        $shopOrderList = [];

                        /** @var ShopOrder $modelShopOrder */
                        foreach ($modelUser->shopOrders0 as $modelShopOrder) {
                            $orderData['amount_of_pizzerias'] = $modelShopOrder->getAmountOfUsers();
                            $orderData['order_uid'] = $modelShopOrder->order_uid;

                            //TODO: что за костыль с user_id ?
                            $shoStatuses = $modelShopOrder->getShopOrderStatuses()->andWhere(['user_id' => $modelUser->id, 'shop_order_id' => $modelShopOrder->getPrimaryKey()])->all();

                            $orderData['status_list'] = [];
                            foreach ($shoStatuses as $status) {
                                $orderData['status_list'][] = $status->getStatusName();
                            }

                            $shopOrderList[] = $orderData;
                        }

                        $htmlOrders = '';

                        if (!$shopOrderList) {
                            $htmlOrders = Yii::t('app', 'No orders');
                        }

                        foreach ($shopOrderList as $orderData) {
                            $htmlOrderAmount = '<div class="order-amount-caption">'
                                . Yii::t('app', 'Total pizzerias: {amount}',
                                    ['amount' => $orderData['amount_of_pizzerias']])
                                . '</div>';
                            $htmlOrderList = '';
                            if ($orderData['status_list']) {
                                foreach ($orderData['status_list'] as $status) {
                                    $htmlOrderList .= '<div class="order-status">' . $status . '</div>';
                                }
                            } else {
                                $htmlOrderList = Yii::t('app', 'No order statuses');
                            }

                            $htmlOrders .= '<div class="wrapper-order-fold">'
                                . '<button class="btn btn-default btn-order-info">' . Yii::t('app', 'Order № {order_uid}', ['order_uid' => $orderData['order_uid']]) . '</button>'
                                . '<div class="order-fold" style="display: none">' . $htmlOrderAmount . $htmlOrderList . '</div>'
                                . '</div>';
                        }

                        return $htmlOrders;
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
