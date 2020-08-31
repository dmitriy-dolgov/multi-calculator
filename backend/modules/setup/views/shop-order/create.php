<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrder */

$this->title = Yii::t('app', 'Create Shop Order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shop Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="make-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
