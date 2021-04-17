<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\UserVirtual */
/* @var $form yii\widgets\ActiveForm */
/* @var $allUsers common\models\db\User */

?>

<div class="user-virtual-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*$form->field($model, 'user_id')->textInput()*/ ?>

    <?= $form->field($model, 'user_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map($allUsers, 'id', 'username'),
        'options' => ['placeholder' => 'Выберите продавца ...'],
        'pluginOptions' => [
            //'placeholder' => 'Выберите пользователя ...',   //Yii::t('app', 'Выберите пользователя ...'),
            'allowClear' => true,
            'multiple' => false,
        ],
    ])->label(Yii::t('app', 'Продавцы')) ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
