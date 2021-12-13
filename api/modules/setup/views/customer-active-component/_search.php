<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CustomerActiveComponentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-active-component-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'component_id') ?>

    <?= $form->field($model, 'price_override') ?>

    <?= $form->field($model, 'price_discount_override') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'unit_id') ?>

    <?php // echo $form->field($model, 'unit_value') ?>

    <?php // echo $form->field($model, 'unit_value_min') ?>

    <?php // echo $form->field($model, 'unit_value_max') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
