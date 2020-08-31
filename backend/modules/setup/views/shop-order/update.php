<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrder */

$this->title = Yii::t('app', 'Update Order: {name}', [
    'name' => $model->deliver_customer_name . ' - ' . $model->order_uid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order List'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => ($model->deliver_customer_name . ' - ' . $model->order_uid), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="make-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
