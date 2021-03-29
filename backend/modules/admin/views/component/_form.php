<?php

use common\models\db\ComponentSwitchGroup;
use common\models\db\Unit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;
use kartik\field\FieldRange;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model common\models\db\Component */
/* @var $modelComponentSet common\models\db\ComponentSet[] */
/* @var $uploadImageForm common\models\UploadComponentImageForm */
/* @var $uploadVideoForm common\models\UploadComponentVideoForm */

//TODO: в контроллер
$modelUnits = Unit::find()->all();

$unitItems = \yii\helpers\ArrayHelper::map($modelUnits, 'id', 'name');
array_walk($unitItems, function (&$name) {
    $name = Yii::t('db', $name);
});

//TODO: в контроллер
$modelComponentSwitchGroups = ComponentSwitchGroup::find()->all();

$componentSwitchGroupsItems = \yii\helpers\ArrayHelper::map($modelComponentSwitchGroups, 'id', 'name');
array_walk($componentSwitchGroupsItems, function (&$name) {
    $name = Yii::t('db', $name);
});

//TODO: в модель
$categories = \common\models\db\Category::find()->all();
$categoryList = \yii\helpers\ArrayHelper::map($categories, 'id', 'name');
?>

<div class="component-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php /*=$form->field($model, 'componentSets')->dropDownList(\yii\helpers\ArrayHelper::map($modelComponentSet,
        'id', 'name'))->label(Yii::t('app', 'Component set'))*/ ?>

    <?= $form->field($model, 'componentSets')->widget(\kartik\select2\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map($modelComponentSet, 'id', 'name'),
        'options' => ['placeholder' => 'Select a set ...'],
        'pluginOptions' => [
            //'placeholder' => 'Select provinces ...',
            'multiple' => true,
        ],
    ])->label(Yii::t('app', 'Component sets')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList($categoryList,
        ['prompt' => Yii::t('app', ' - Not selected - ')])->label(Yii::t('app', 'Category')) ?>

    <?= $form->field($model, 'price')->widget(NumberControl::class, [
        'maskedInputOptions' => [
            'suffix' => ' ₽',
            'allowMinus' => false,
            'rightAlign' => false,
        ],
        'displayOptions' => ['class' => 'form-control kv-monospace'],
    ]);
    ?>

    <?= $form->field($model, 'price_discount')->widget(NumberControl::class, [
        'maskedInputOptions' => [
            'suffix' => ' ₽',
            'allowMinus' => false,
            'rightAlign' => false,
        ],
        'displayOptions' => ['class' => 'form-control kv-monospace'],
    ]);
    ?>

    <?= $form->field($model, 'disabled')->widget(\kartik\switchinput\SwitchInput::classname(), [
        'pluginOptions' => [
            'handleWidth' => 80,
            'onText' => Yii::t('app', 'Disabled'),
            'offText' => Yii::t('app', 'Active'),
        ]
    ]); ?>

    <?= $form->field($model, 'amount')->hint(Yii::t('app',
        'The information field, for now, can only be changed manually.')) ?>

    <hr>

    <?php /*FieldRange::widget([
        'form' => $form,
        'model' => $model,
        'label' => Yii::t('app', 'Valid range of components for selection'),
        'attribute1' => 'item_select_min',
        'attribute2' => 'item_select_max',
        'type' => FieldRange::INPUT_SPIN,
    ])*/ ?>

    <hr>

    <?= $form->field($model, 'unit_id')->dropDownList($unitItems,
        ['prompt' => Yii::t('app', ' - Not selected - ')])->label(Yii::t('app', 'Unit')) ?>

    <?= $form->field($model, 'unit_value')->widget(NumberControl::class, [
        'maskedInputOptions' => [
            'allowMinus' => false,
            'rightAlign' => false,
        ],
        'displayOptions' => ['class' => 'form-control kv-monospace'],
    ])->label(Yii::t('app', 'The number of units in one component'));
    ?>

    <?php /*= FieldRange::widget([
        'form' => $form,
        'model' => $model,
        'label' => Yii::t('app', 'Range of allowable units'),
        'attribute1' => 'unit_value_min',
        'attribute2' => 'unit_value_max',
        'type' => FieldRange::INPUT_SPIN,
    ]) */ ?>

    <?= $form->field($model, 'unit_switch_group')->dropDownList($componentSwitchGroupsItems,
        ['prompt' => Yii::t('app', ' - Not selected - ')])->label(Yii::t('app',
        'Alternative component group')) ?>

    <hr>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 3]) ?>

    <?php

    $imgConfig = [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showUpload' => false,
            //'showRemove' => false,
            'overwriteInitial' => false,
            'initialPreviewAsData' => true,
        ],

    ];

    $imgPath = Url::to([Yii::$app->params['component_images']['url_path']], true) . '/';

    foreach ($model->componentImages as $key => $componentImage) {

        $imgConfig['pluginOptions']['initialPreview'][] = $imgPath . $componentImage->relative_path;

        $imgConfig['pluginOptions']['initialPreviewConfig'][] = [
            'url' => Url::to(['/setup/component/image-delete', 'id' => $componentImage->getPrimaryKey()]),
        ];
    }

    echo $form->field($uploadImageForm, 'imageFiles[]')
        ->widget(\kartik\file\FileInput::className(), $imgConfig)
        ->label(Yii::t('app', 'Image files'));
    ?>

    <br>

    <?php

    //TODO: добавить и видео потом (сейчас вместо видео будут GIF)

    $videoConfig = [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showUpload' => false,
            //'showRemove' => false,
            'overwriteInitial' => false,
            'initialPreviewAsData' => true,
        ],
    ];

    $videoPath = Url::to([\common\models\UploadComponentVideoForm::videoPathUrl()], true) . '/';

    foreach ($model->componentVideos as $key => $componentVideo) {

        $videoConfig['pluginOptions']['initialPreview'][] = $videoPath . $componentVideo->relative_path;

        $videoConfig['pluginOptions']['initialPreviewConfig'][] = [
            'url' => Url::to(['/setup/component/video-delete', 'id' => $componentVideo->getPrimaryKey()]),
        ];
    }

    echo $form->field($uploadVideoForm, 'videoFiles[]')
        ->widget(\kartik\file\FileInput::class, $videoConfig)
        ->label(Yii::t('app', 'Animated files'));
    ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
