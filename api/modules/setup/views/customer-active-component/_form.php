<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

//use kartik\nav\NavX;

/* @var $this yii\web\View */
/* @var $model common\models\db\CustomerActiveComponent */
/* @var $form yii\widgets\ActiveForm */

$components = \common\models\db\Component::findAll(['user_id' => null]);
foreach ($components as $compo) {
    if ($compo->category) {
        if (!isset($selectComponentsData[$compo->category->name])) {
            $selectComponentsData[$compo->category->name] = [];
        }
        $selectComponentsData[$compo->category->name][$compo->id] = $compo->name;
    } else {
        $selectComponentsData[Yii::t('app', 'No category')][$compo->id] = $compo->name;
    }
}

$this->registerJs(<<<JS
gl.functions.setUpOriginalComponentInfo = function(componentId) {
    alert(componentId);
}
JS
);

?>

<div class="customer-active-component-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*= $form->field($model, 'component_id')->textInput()*/ ?>

    <?= $form->field($model, 'component_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => $selectComponentsData,
        'options' => ['placeholder' => 'Select a component ...'],
    ])->label(Yii::t('app', 'Components')) ?>

    <hr>

    <div class="original-price"></div>

    <?= $form->field($model, 'price_override')->textInput(['maxlength' => true]) ?>

    <hr>

    <div class="original-price_discount"></div>

    <?= $form->field($model, 'price_discount_override')->textInput(['maxlength' => true]) ?>

    <?php /* ?>
    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'unit_id')->textInput() ?>

    <?= $form->field($model, 'unit_value')->textInput() ?>

    <?= $form->field($model, 'unit_value_min')->textInput() ?>

    <?= $form->field($model, 'unit_value_max')->textInput() ?>
    <?php */ ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
