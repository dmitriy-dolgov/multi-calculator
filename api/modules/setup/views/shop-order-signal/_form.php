<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\ShopOrderSignal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-order-signal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'emails')->textarea(['rows' => 6])->hint(Yii::t('app',
        'You can specify several, one on each row')) ?>

    <?= $form->field($model, 'phones')->textarea(['rows' => 6])->hint(Yii::t('app',
        'You can specify several, one on each row')) ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
