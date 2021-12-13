<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\Geopoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="geopoint-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_region')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code_cdek')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kladr_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fias_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lat_long')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'merchant_coverage_radius')->textInput() ?>

    <?= $form->field($model, 'index')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code_boxberry')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code_dpd')->textInput(['maxlength' => true]) ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
