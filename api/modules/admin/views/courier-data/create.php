<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierData */

$this->title = Yii::t('app', 'Создать данные курьера');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Данные курьера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courier-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
