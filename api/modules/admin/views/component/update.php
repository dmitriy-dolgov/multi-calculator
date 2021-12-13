<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\Component */
/* @var $modelComponentSet common\models\db\ComponentSet[] */
/* @var $modelUnits common\models\db\Unit[] */
/* @var $uploadImageForm common\models\UploadComponentImageForm */
/* @var $uploadVideoForm common\models\UploadComponentVideoForm */

$this->title = Yii::t('app', 'Update Component: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Components'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="component-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelComponentSet' => $modelComponentSet,
        'modelUnits' => $modelUnits,
        'uploadImageForm' => $uploadImageForm,
        'uploadVideoForm' => $uploadVideoForm,
    ]) ?>

</div>
