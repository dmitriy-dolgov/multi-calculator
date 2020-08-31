<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="make-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'order_uid') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'deliver_address') ?>

    <?php // echo $form->field($model, 'deliver_customer_name') ?>

    <?php // echo $form->field($model, 'deliver_phone') ?>

    <?php // echo $form->field($model, 'deliver_email') ?>

    <?php // echo $form->field($model, 'deliver_comment') ?>

    <?php // echo $form->field($model, 'deliver_required_time_begin') ?>

    <?php // echo $form->field($model, 'deliver_required_time_end') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
