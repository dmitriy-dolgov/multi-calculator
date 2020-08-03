<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CustomerActiveComponent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-active-component-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'component_id')->textInput() ?>

    <?= $form->field($model, 'price_override')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_discount_override')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'unit_id')->textInput() ?>

    <?= $form->field($model, 'unit_value')->textInput() ?>

    <?= $form->field($model, 'unit_value_min')->textInput() ?>

    <?= $form->field($model, 'unit_value_max')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
