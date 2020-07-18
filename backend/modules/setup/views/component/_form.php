<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use kartik\number\NumberControl;
use kartik\field\FieldRange;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model common\models\db\Component */
/* @var $modelUnits common\models\db\Unit[] */
/* @var $modelComponentSwitchGroups common\models\db\ComponentSwitchGroup[] */
/* @var $uploadImageForm common\models\UploadComponentImageForm */
/* @var $uploadVideoForm common\models\UploadComponentVideoForm */

$unitItems = \yii\helpers\ArrayHelper::map($modelUnits, 'id', 'name');
array_walk($unitItems, function (&$name) {
    $name = Yii::t('db', $name);
});

$componentSwitchGroupsItems = \yii\helpers\ArrayHelper::map($modelComponentSwitchGroups, 'id', 'name');
array_walk($componentSwitchGroupsItems, function (&$name) {
    $name = Yii::t('db', $name);
});

?>

<div class="component-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>

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
    ])->label(Yii::t('app', 'Activity')); ?>

    <?= $form->field($model, 'amount')->widget(NumberControl::class, [
        'maskedInputOptions' => [
            'digits' => 0,
            'allowMinus' => false,
            'rightAlign' => false,
        ],
        'displayOptions' => ['class' => 'form-control kv-monospace'],
    ])->hint(Yii::t('app',
        'The information field, for now, can only be changed manually.'));
    ?>

    <hr>

    <?= FieldRange::widget([
        'form' => $form,
        'model' => $model,
        'label' => Yii::t('app', 'Valid range of components for selection'),
        'attribute1' => 'item_select_min',
        'attribute2' => 'item_select_max',
        'type' => FieldRange::INPUT_SPIN,
    ]) ?>

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
    /*echo \kartik\file\FileInput::widget([
        'name' => 'imageFile',
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'uploadUrl' => \yii\helpers\Url::to(['component/file-upload']),
            'initialPreview' => [
                "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/FullMoon2010.jpg/631px-FullMoon2010.jpg",
                "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Earth_Eastern_Hemisphere.jpg/600px-Earth_Eastern_Hemisphere.jpg"
            ],
            'initialPreviewAsData' => true,
            'initialCaption' => "The Moon and the Earth",
//            'initialPreviewConfig' => [
//                ['caption' => 'Moon.jpg', 'size' => '873727'],
//                ['caption' => 'Earth.jpg', 'size' => '1287883'],
//            ],
            'overwriteInitial' => false,
            'maxFileSize' => 2800
        ]
    ]);*/
    ?>

    <?php /*$form->field($componentImage, 'relative_path[]')->widget(\kartik\file\FileInput::className(), [
        'options' => [
            'multiple' => true,
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showUpload' => false,
            'showRemove' => false,
        ]
    ])*/ ?>

    <hr>

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

    //$initialPreviewConfig = [];

    $imgPath = Url::to([Yii::$app->params['component_images']['url_path']], true) . '/';

    foreach ($model->componentImages as $key => $componentImage) {
        /*$imgConfig['initialPreviewConfig'] => [
            ['caption' => 'Moon.jpg', 'size' => '873727'],
            ['caption' => 'Earth.jpg', 'size' => '1287883'],
        ],*/

        $imgConfig['pluginOptions']['initialPreview'][] = $imgPath . $componentImage->relative_path;

        /*$imgConfig['initialPreviewConfig'][] = [
            'key' => $key,
            'filetype' => $componentImage->mime_type,
            'caption' => $componentImage->name,
            'size' => strlen($componentImage->content),
            //'url' => Url::to([$this->fileUploadLink, 'id' => $file->id, 'action' => 'remove']),
            //'downloadUrl' => Url::to([$this->fileUploadLink, 'id' => $file->id, 'action' => 'download']),
        ];*/

        $imgConfig['pluginOptions']['initialPreviewConfig'][] = [
            'url' => Url::to(['/setup/component/image-delete', 'id' => $componentImage->getPrimaryKey()]),
        ];
    }

    /*if ($initialPreviewConfig) {
        $imgConfig['initialPreviewConfig'] = $initialPreviewConfig;
    }*/

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
        ->widget(\kartik\file\FileInput::className(), $videoConfig)
        ->label(Yii::t('app', 'Animated files'));
    ?>


    <?php /*$form->field($model, 'price')->textInput(['maxlength' => true])*/ ?>

    <?php /*$form->field($model, 'price_discount')->textInput(['maxlength' => true])*/ ?>

    <?php /*= $form->field($model, 'parent_component_id')->textInput()*/ ?>

    <?php /*= $form->field($model, 'category_id')->textInput()*/ ?>

    <?php /*= $form->field($model, 'deleted_at')->textInput()*/ ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
