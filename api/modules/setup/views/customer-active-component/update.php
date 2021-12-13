<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CustomerActiveComponent */

$componentName = $model->component ? $model->component->name : Yii::$app->formatter->nullDisplay;

$this->title = Yii::t('app', 'Update Chosen Component: {name}', [
    'name' => $componentName,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Chosen Components'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $componentName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-active-component-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
