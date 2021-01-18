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
?>

<?php //$this->beginContent('@Da/User/resources/views/shared/admin_layout.php') ?>

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
                            $usersText = '<div class="amount-caption">'
                                . Yii::t('app', 'Total pizzerias: {amount}',
                                    ['amount' => $modelShopOrder->getAmountOfUsers()])
                                . '</div>';

                            //TODO: что за костыль с user_id ?
                            $shoStatuses = $modelShopOrder->getShopOrderStatuses()->andWhere(['user_id' => $modelUser->id, 'shop_order_id' => $modelShopOrder->getPrimaryKey()])->all();
                            //Yii::$app->user->identity->getShopOrderStatuses()

                            $statusList = [];
                            foreach ($shoStatuses as $status) {
                                $statusList[] = $status->getStatusName();
                            }

                            if ($statusList) {
                                $shopOrderList[] = $usersText . '<hr>' . implode('<br>', $statusList);
                            } else {
                                $shopOrderList[] = Yii::t('app', 'No order statuses');
                            }

                        }

                        if (!$shopOrderList) {
                            $shopOrderList[] = Yii::t('app', 'No orders');
                        }

                        return implode('<hr><hr>', $shopOrderList);
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

<?php //$this->endContent() ?>
