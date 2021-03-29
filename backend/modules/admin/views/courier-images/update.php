<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierImages */
/* @var $uploadCourierImageForm common\models\UploadCourierImageForm */

$this->title = Yii::t('app', 'Обновить изображение курьера: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Изображение курьера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Обновить');
?>
<div class="courier-images-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'uploadCourierImageForm' => $uploadCourierImageForm,
    ]) ?>

</div>
