<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $emailForm common\models\forms\EmailForm */

$this->title = Yii::t('app', 'Archive');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archive-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($emailForm, 'email') ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Create archive'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
