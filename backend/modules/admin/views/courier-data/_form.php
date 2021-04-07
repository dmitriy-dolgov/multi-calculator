<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courier-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_of_courier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description_of_courier')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'photo_of_courier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'courier_in_move')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'courier_is_waiting')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'velocity')->textInput() ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
