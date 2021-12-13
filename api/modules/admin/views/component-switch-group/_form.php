<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\ComponentSwitchGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="component-switch-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*= $form->field($model, 'user_id')->textInput() */?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
