<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorkerDeclineCause */
/* @var $form yii\widgets\ActiveForm */
/* @var $coWorkers common\models\db\CoWorker[] */

$coWorkersDropData = \yii\helpers\ArrayHelper::map($coWorkers, 'id', 'name');

$coWorkerFunctionsList = $model->coWorker ? $model->coWorker->getCoWorkerFunctionsList(false) : [Yii::$app->formatter->nullDisplay];

?>

<div class="co-worker-decline-cause-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'co_worker_id')->dropDownList($coWorkersDropData, [
        'prompt' => Yii::t('app', '-- Not set --'),
    ])->label(Yii::t('app', 'Co-Worker')) ?>

    <?= Yii::t('app', 'Co-worker functions:') .
        $model->coWorker->getCoWorkerFunctionsHtml()
    ?>

    <?= $form->field($model, 'cause')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'order')->textInput()->hint(
        Yii::t('app', 'Priority to show in the list. Set up highther priority to more often selected causes.')
    ) ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
