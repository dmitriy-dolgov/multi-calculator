<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrderSignal */

$this->title = Yii::t('app', 'Contacts for notification of new orders');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signal Contacts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="shop-order-signal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'emails:ntext',
            'phones:ntext',
        ],
    ]) ?>

</div>
