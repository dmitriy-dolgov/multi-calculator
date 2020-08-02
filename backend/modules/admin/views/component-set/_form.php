<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\ComponentSet */
/* @var $form yii\widgets\ActiveForm */

$jsStrings = [
    'Are you sure you want to delete component "{name}"?' => json_encode(Yii::t('app',
        'Are you sure you want to delete component "{name}"?')),
];

$this->registerJs(<<<JS
gl.functions.deleteComponentFromSet = function(id, name) {
    var str = {$jsStrings['Are you sure you want to delete component "{name}"?']}.replace(/{name}/, name);
    if (confirm(str)) {
      return true;
    }
    
    return false;
}
JS
);

?>

<div class="component-set-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php

    $componentSetHtml = Yii::t('app', 'No components yet');
    if (!empty($model->components)) {
        $componentSetHtml = [];
        foreach ($model->components as $component) {
            $componentSetHtml[] = Html::a(Html::img($component->getImageUrl(), [
                        'style' => 'width:50px;margin-right:10px;',
                    ]) . $component->name,
                    ['component/view', 'id' => $component->id],
                    ['target' => '_blank'])
                . Html::tag('div', Yii::t('app', 'Delete'), [
                    'onclick' => 'gl.functions.deleteComponentFromSet(' . $component->id . ', ' . json_encode($component->name) . ')',
                ]);
        }
        $componentSetHtml = implode('<br>', $componentSetHtml);
    }

    echo $componentSetHtml;

    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
