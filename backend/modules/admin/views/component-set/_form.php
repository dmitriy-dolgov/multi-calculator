<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\ComponentSet */
/* @var $form yii\widgets\ActiveForm */

$jsStrings = [
    'Are you sure you want to delete component "{name}"?' => json_encode(Yii::t('app',
        'Are you sure you want to delete component "{name}"?')),
    "Couldn't remove component from set." => json_encode(Yii::t('app', "Couldn't remove component from set.")),
];

$this->registerJs(<<<JS
gl.functions.deleteComponentFromSet = function(setId, componentId, name) {
    var str = {$jsStrings['Are you sure you want to delete component "{name}"?']}.replace(/{name}/, name);
    if (confirm(str)) {
        $.post('/admin/component-set/remove-component-from-set', {
            ajax: 1,
            setId: setId,
            componentId:componentId
        }, function(data) {
            if (data.result) {
                $('.component-panel[data-id=' + componentId + ']').fadeOut();
            } else {
                alert({$jsStrings["Couldn't remove component from set."]});
            }
        }).fail(function(xhr, status, error) {
            // error handling
            alert('Error: ' + error);
        });
        
        return true;
    }
    
    return false;
}
JS
);

$this->registerCss(<<<CSS
.btn-delete-component {
    cursor: pointer;
    padding: 7px 15px;
    border: black 1px solid;
    font-weight: bold;
    margin: 7px 0 7px auto;
    background-color: #2C4257;
    color: white;
    border-radius: 5px;
}
.btn-delete-component:hover {
    color: red;
}
.component-panel {
    display: flex;
    justify-items: center;
    margin-bottom: 7px;
}
.component-panel.odd {
    background-color: white;
}
CSS
);

?>

<div class="component-set-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <br>

    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Component list') ?></label>
        <?php

        $componentSetHtml = Yii::t('app', 'No components yet');
        if (!empty($model->components)) {
            $componentSetHtml = '';
            $odd = true;
            foreach ($model->components as $component) {
                $componentSetHtml .= Html::tag('div', Html::a(Html::img($component->getImageUrl(), [
                            'style' => 'width:50px;margin-right:10px;',
                        ]) . $component->name,
                        ['component/view', 'id' => $component->id],
                        ['target' => '_blank'])
                    . Html::tag('div', Yii::t('app', 'Delete'), [
                        'class' => 'btn-delete-component',
                        'onclick' => 'gl.functions.deleteComponentFromSet(' . $model->id . ', ' . $component->id . ', ' . json_encode($component->name) . ')',
                    ]), [
                    'class' => 'component-panel' . ($odd ? ' odd' : ''),
                    'data-id' => $component->id,
                ]);

                $odd = !$odd;
            }
            //$componentSetHtml = implode('<br>', $componentSetHtml);
        }

        echo $componentSetHtml;

        ?>

        <div class="help-block"></div>
    </div>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
