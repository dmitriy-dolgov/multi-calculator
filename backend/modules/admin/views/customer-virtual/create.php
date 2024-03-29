<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CustomerVirtual */

$this->title = Yii::t('app', 'Create Customer Virtual');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Virtuals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-virtual-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
