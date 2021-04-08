<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courier-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php // $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'name_of_courier') ?>

    <?= $form->field($model, 'description_of_courier') ?>

    <?= $form->field($model, 'photo_of_courier') ?>

    <?= $form->field($model, 'courier_in_move') ?>

    <?= $form->field($model, 'courier_is_waiting') ?>

    <?= $form->field($model, 'velocity') ?>

    <?= $form->field($model, 'priority') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
