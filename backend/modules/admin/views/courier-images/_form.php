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

    $imgPath = Url::to([Yii::$app->params['order_map']['courier']['images']['url_path']], true) . '/';

    $imgConfig = [];
    //$model->run
    //foreach ($model->componentImages as $key => $componentImage) {

    if ($model->run) {
        $imgConfig['pluginOptions']['initialPreview'] = $imgPath . $model->run;
    }

        /*$imgConfig['pluginOptions']['initialPreviewConfig'][] = [
            'url' => Url::to(['/setup/component/image-delete', 'id' => $componentImage->getPrimaryKey()]),
        ];*/
    //}

    echo $form->field($uploadCourierImageForm, 'imageFile')
        ->widget(FileInput::className(), $imgConfig)
        ->label(Yii::t('app', 'Изображение курьера в пути'));
    ?>

    <?= $form->field($model, 'wait')->textInput(['maxlength' => true]) ?>

    <?php /*$form->field($model, 'disabled_at')->textInput()*/ ?>

    <?= $form->field($model, 'disabled_at')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            //'handleWidth' => 80,
            'onText' => Yii::t('app', 'Disabled'),
            'offText' => Yii::t('app', 'Active'),
        ]
    ]); ?>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
