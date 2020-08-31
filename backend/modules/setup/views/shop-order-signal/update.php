<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrderSignal */

$this->title = Yii::t('app', 'Update contacts for notification of new orders');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Order Signals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="make-order-signal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
