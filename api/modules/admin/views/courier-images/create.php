<?php

use common\models\UploadComponentImageForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierImages */
/* @var $uploadCourierImageForm common\models\UploadCourierImageForm */

$this->title = Yii::t('app', 'Создать изображения курьера');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Изображение курьера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courier-images-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'uploadCourierImageForm' => $uploadCourierImageForm,
    ]) ?>

</div>
