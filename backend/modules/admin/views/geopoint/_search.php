<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\GeopointSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="geopoint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'region') ?>

    <?= $form->field($model, 'sub_region') ?>

    <?= $form->field($model, 'code_cdek') ?>

    <?php // echo $form->field($model, 'kladr_code') ?>

    <?php // echo $form->field($model, 'uuid') ?>

    <?php // echo $form->field($model, 'fias_uuid') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'region_code') ?>

    <?php // echo $form->field($model, 'lat_long') ?>

    <?php // echo $form->field($model, 'merchant_coverage_radius') ?>

    <?php // echo $form->field($model, 'index') ?>

    <?php // echo $form->field($model, 'code_boxberry') ?>

    <?php // echo $form->field($model, 'code_dpd') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
