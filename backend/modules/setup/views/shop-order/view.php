<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\db\ShopOrder;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrder */

$this->title = $model->deliver_customer_name . ' - ' . $model->order_uid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerCss(<<<CSS
.make-order-view td hr {
    margin: 7px 0;
    border-top: 1px solid #848484;
}
CSS
);

$changeStatusUrl = json_encode(Url::to(['/setup/make-order/status-change']));

//TODO: обработка ошибок типа 404
$this->registerJs(<<<JS
gl.functions.changeOrderStatus = function(shopOrderModelId, newStatusName) {
    $.post($changeStatusUrl, 
        {shopOrderModelId: shopOrderModelId, newStatusName: newStatusName},
        function (data) {
            window.location = window.location;
            /*if (data.success) {
                window.location = window.location;
            } else {
                alert('Error!');
            }*/
        }
    );
}
JS
);

$htmlTexts['acceptOrder'] = Html::encode(Yii::t('app', 'Offer to complete the order'));
$htmlTexts['offer-sent-wait-for-approval'] = Html::encode(Yii::t('app', 'Offer sent, wait for approval.'));
$htmlTexts['order-cancelled-by-user'] = Html::encode(Yii::t('app', 'Order cancelled by user.'));

?>
<div class="make-order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php /*Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])*/ ?>
        <?php /*Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])*/ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'user_id',
            'order_uid',
            'created_at',
            'deliver_customer_name',
            'deliver_address:ntext',
            'deliver_phone',
            'deliver_email:email',
            'deliver_comment:ntext',
            'deliver_required_time_begin',
            'deliver_required_time_end',
            [
                'label' => Yii::t('app', 'Order Components'),
                'format' => 'raw',
                'value' => function ($model) {
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
                'format' => 'raw',
                'value' => function ($model) use ($htmlTexts) {
                    $usersText = '<div class="amount-caption">'
                        . Yii::t('app', 'Total pizzerias: {amount}', ['amount' => $model->getAmountOfUsers()])
                        . '</div>';

                    $shoStatuses = Yii::$app->user->identity->getShopOrderStatuses()->andWhere(['shop_order_id' => $model->getPrimaryKey()])->all();

                    $statusList = [];
                    foreach ($shoStatuses as $key => $status) {
                        $statusList[] =  ($key + 1) . '-й статус: ' . $status->getStatusName();
                    }

                    //$usersText .= '<hr>' . $status->getHandleStatusHtml();

                    $usersText .= '<hr>' . implode('<hr>', $statusList);

                    $usersText .= '<hr>';

                    if ($status->type == 'created') {
                        $usersText .= <<<HTML
    <button class="btn-status-change" onclick="gl.functions.changeOrderStatus({$model->id}, 'offer-sent-to-customer');return false;">{$htmlTexts['acceptOrder']}</button>
HTML;
                    } elseif ($status->type == 'offer-sent-to-customer') {
                        $usersText .= <<<HTML
    {$htmlTexts['offer-sent-wait-for-approval']}
HTML;
                    }
                    elseif ($status->type == 'order-cancelled-by-user') {
                        $usersText .= <<<HTML
    {$htmlTexts['order-cancelled-by-user']}
HTML;
                    }

                    return $usersText;
                },
            ],
        ],
    ]) ?>

</div>
