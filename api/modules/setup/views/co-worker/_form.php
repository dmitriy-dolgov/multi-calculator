<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorker */
/* @var $form yii\widgets\ActiveForm */
/* @var $coWorkerFunctions common\models\db\CoWorkerFunction[] */

$coWorkerFunctionsItems = [];
foreach ($coWorkerFunctions as $cwf) {
    $coWorkerFunctionsItems[$cwf->id] = Yii::t('db', $cwf->name);
}

?>

<div class="co-worker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coWorkerFunctions')
        ->checkboxList($coWorkerFunctionsItems, ['separator' => '<br/>'])
        ->label(Yii::t('app', "Co-worker's functions")) ?>

    <?= $form->field($model, 'worker_site_uid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthday')->widget(\kartik\date\DatePicker::class, [
        'options' => ['placeholder' => Yii::t('app', 'Enter birth date ...')],
        'pluginOptions' => [
            'autoclose' => true,
        ]
    ]);
    ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
