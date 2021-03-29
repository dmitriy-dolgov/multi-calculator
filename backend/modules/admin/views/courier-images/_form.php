<?php

use kartik\file\FileInput;
use kartik\switchinput\SwitchInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\db\CourierImages */
/* @var $form yii\widgets\ActiveForm */
/* @var $uploadCourierImageForm common\models\UploadCourierImageForm */
?>

<div class="courier-images-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*$form->field($model, 'run')->textInput(['maxlength' => true])*/ ?>

    <?php

    $imgConfig = [
        'options' => [
            'multiple' => false,
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

    if ($model->run) {
        $imgPath = Url::to([Yii::$app->params['order_map']['courier']['images']['url_path']], true) . '/';
        $imgConfig['pluginOptions']['initialPreview'] = $imgPath . $model->run;

        $imgConfig['pluginOptions']['initialPreviewConfig'] = [
            'url' => Url::to(['/setup/component/image-delete', 'id' => $model->getPrimaryKey()]),
        ];
    }

    /*$imgConfig['pluginOptions']['initialPreviewConfig'][] = [
        'url' => Url::to(['/setup/component/image-delete', 'id' => $componentImage->getPrimaryKey()]),
    ];*/
    //}

    //echo $form->field($uploadCourierImageForm, 'imageFile')
    echo $form->field($uploadCourierImageForm, 'imageFile')
        ->widget(FileInput::class, $imgConfig)
        ->label(Yii::t('app', 'Изображение курьера в пути'));
    ?>

    <?= $form->field($model, 'wait')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disabled_at')->widget(SwitchInput::class, [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Disabled'),
            'offText' => Yii::t('app', 'Active'),
        ]
    ])->label(Yii::t('app', 'Активность')); ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
