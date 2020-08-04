<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="co-worker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'worker_site_uid')->textInput(['maxlength' => true]) ?>

    <?php /*$form->field($model, 'birthday')->textInput()*/ ?>

    <?= $form->field($model, 'birthday')->widget(\kartik\date\DatePicker::class, [
        'options' => ['placeholder' => Yii::t('app', 'Enter birth date ...')],
        'pluginOptions' => [
            'autoclose' => true,
        ]
    ]);
    ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
