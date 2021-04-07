<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courier-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'old_user_id')->textInput() ?>

    <?= $form->field($model, 'new_user_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
