<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\db\Texts */
/* @var $form yii\widgets\ActiveForm */

//TODO: создать отдельные таблицы для этих значений
$model->group = 'agreement';
$model->type = 'html';

?>

<div class="texts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?php /*$form->field($model, 'data')->textarea(['rows' => 6]) */?>

    <?= $form->field($model, 'data')->widget(Widget::class, [
        'settings' => [
            'lang' => 'ru', //TODO: при интернационализации менять
            'minHeight' => 350,
            /*'plugins' => [
                'clips',
                'fullscreen',
            ],*/
            'clips' => [
                ['Lorem ipsum...', 'Lorem...'],
                ['red', '<span class="label-red">red</span>'],
                ['green', '<span class="label-green">green</span>'],
                ['blue', '<span class="label-blue">blue</span>'],
            ],
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
