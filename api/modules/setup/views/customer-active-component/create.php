<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CustomerActiveComponent */

$this->title = Yii::t('app', 'Add Chosen Component');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Chosen Components'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-active-component-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
