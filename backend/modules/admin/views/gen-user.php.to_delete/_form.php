<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\db\Category;

/* @var $this yii\web\View */
/* @var $model common\models\db\Category */
/* @var $form yii\widgets\ActiveForm */

$categories = \yii\helpers\ArrayHelper::map(Category::find()->asArray()->all(), 'id', 'name');

if (!$model->isNewRecord) {
    unset($categories[$model->getPrimaryKey()]);
    foreach ($model->categories as $cat) {
        unset($categories[$cat->getPrimaryKey()]);
    }
}

?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_category_id')
        ->dropDownList($categories, ['prompt' => Yii::t('app', '-- No category --')])->label(Yii::t('app',
            'Parent category')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
