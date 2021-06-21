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
    "Couldn't add component to set." => json_encode(Yii::t('app', "Couldn't add component to set.")),
];

$this->registerJs(<<<JS
gl.functions.deleteComponentFromSet = function(setId, componentId, name) {
    var str = {$jsStrings['Are you sure you want to delete component "{name}"?']}.replace(/{name}/, name);
    if (confirm(str)) {
        $.post('/admin/component-set/remove-component-from-set', {
            ajax: 1,
            setId: setId,
            componentId: componentId
        }, function(data) {
            if (data.result) {
                //$('.component-panel[data-id=' + componentId + ']').fadeOut();
                location.reload();
            } else {
                alert({$jsStrings["Couldn't remove component from set."]});
            }
        }).fail(function(xhr, status, error) {
            alert('Error: ' + error);
        });
        
        return true;
    }
    
    return false;
};

gl.functions.addComponentToSet = function(setId, componentId) {
    $.post('/admin/component-set/add-component-to-set', {
            ajax: 1,
            setId: setId,
            componentId: componentId
        }, function(data) {
            if (data.result) {
                //$('.component-panel.to-add[data-id=' + componentId + ']').fadeOut();
                location.reload();
            } else {
                alert({$jsStrings["Couldn't add component to set."]});
            }
        }).fail(function(xhr, status, error) {
            //alert('Error: ' + status.toSource());
            //console.debug('Error: ', status.xhr(), error.status(), );
            gl.debug([xhr, status, error]);
        });
}
JS
);

$this->registerCss(<<<CSS
.btn-delete-component,
.btn-component-to-add {
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
.btn-component-to-add {
    background-color: #ffb700;
    color: black;
    margin: 0;
}
.btn-component-to-add:hover {
    color: white;
}
.component-panel {
    display: flex;
    align-items: center;
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
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <hr>

    <div class="form-group">
        <label class="control-label"><?= Yii::t('app', 'Component list in the set') ?></label>
        <?php

        $componentInSetIds = [];

        $componentSetHtml = Yii::t('app', 'No components yet');
        if (!empty($model->components)) {
            $componentSetHtml = '';
            $odd = true;
            foreach ($model->components as $component) {
                $componentInSetIds[] = $component->id;
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
        <label class="control-label"><?= Yii::t('app', 'Component list to add') ?></label>
        <?php

        $naComponents = \common\models\db\Component::find()->where(['user_id' => null])->andWhere([
            'not in',
            'id',
            $componentInSetIds
        ])->all();

        $naComponentsHtml = Yii::t('app', 'No components');
        if (!empty($model->components)) {
            $naComponentsHtml = '';
            $odd = true;
            foreach ($naComponents as $component) {
                $naComponentsHtml .= Html::tag('div', /*\kartik\checkbox\CheckboxX::widget([
                        'name' => 'component-to-add-' . $component->id,
                        'options' => [
                            'id' => 'ct-sel-all',
                            'class' => 'btn-component-to-add',
                        ],
                        'pluginOptions' => ['threeState' => false],
                    ]) . '<label class="cbx-label" for="component-to-add-' . $component->id . '">' . Yii::t('app',
                        'Add to set') . '</label>'*/
                    Html::tag('div', Yii::t('app',
                        'Add to set'), [
                        'class' => 'btn-component-to-add',
                        'onclick' => 'gl.functions.addComponentToSet(' . $model->id . ', ' . $component->id . ')',
                    ])
                    . Html::a(Html::img($component->getImageUrl(),
                            ['style' => 'width:70px;margin:0 10px;']) . Html::encode($component->name),
                        ['component/view', 'id' => $component->id], ['target' => '_blank']),
                    [
                        'class' => 'component-panel to-add',
                        'style' => 'margin-top: 7px;',
                    ]);

                $odd = !$odd;
            }
        }

        echo $naComponentsHtml;

        ?>

    </div>


</div>
