<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierImages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courier-images-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'run')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wait')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disabled_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
