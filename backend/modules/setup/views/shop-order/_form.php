<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="make-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*$form->field($model, 'user_id')->textInput()*/ ?>

    <?= $form->field($model, 'order_uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'deliver_customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deliver_address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'deliver_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deliver_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deliver_comment')->textarea(['rows' => 6]) ?>

    <hr>

    <?= $form->field($model, 'deliver_required_time_begin')->textInput() ?>

    <?= $form->field($model, 'deliver_required_time_end')->textInput() ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
