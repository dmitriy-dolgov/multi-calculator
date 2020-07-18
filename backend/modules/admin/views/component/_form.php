<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model common\models\db\Component */
/* @var $modelComponentSet common\models\db\ComponentSet[] */
/* @var $uploadImageForm common\models\UploadComponentImageForm */
/* @var $uploadVideoForm common\models\UploadComponentVideoForm */
?>

<div class="component-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'componentSets')->dropDownList(\yii\helpers\ArrayHelper::map($modelComponentSet,
        'id', 'name'))->label(Yii::t('app', 'Component set')) ?>

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
    ]); ?>

    <?= $form->field($model, 'amount')->hint(Yii::t('app',
        'The information field, for now, can only be changed manually.')) ?>

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
        ->widget(\kartik\file\FileInput::className(), $videoConfig)
        ->label(Yii::t('app', 'Animated files'));
    ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
