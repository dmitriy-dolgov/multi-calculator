<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierImages */

$this->title = Yii::t('app', 'Создать изображение курьера');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Изображение курьера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courier-images-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
