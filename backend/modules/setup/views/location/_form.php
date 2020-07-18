<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use andkon\yii2kladr\Kladr;

/* @var $this yii\web\View */
/* @var $model common\models\db\Location */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'region_id')
        ->widget(Kladr::class, [
            'type' => Kladr::TYPE_REGION,
            'options' => [
                'value' => $model->region,
                'placeHolder' => $model->getAttributeLabel('region'),
                'class' => 'form-control',
            ]
        ])
        ->label($model->getAttributeLabel('region'));
    ?>

    <?= $form->field($model, 'district_id')
        ->widget(Kladr::class, [
            'type' => Kladr::TYPE_DISTRICT,
            'options' => [
                'value' => $model->district,
                'placeHolder' => $model->getAttributeLabel('district'),
                'class' => 'form-control',
            ]
        ])
        ->label($model->getAttributeLabel('district'));
    ?>


    <?= $form->field($model, 'city_id')
        ->widget(Kladr::class, [
            'type' => Kladr::TYPE_CITY,
            'options' => [
                'value' => $model->city,
                'placeHolder' => $model->getAttributeLabel('city'),
                'class' => 'form-control',
            ]
        ])
        ->label($model->getAttributeLabel('city'));
    ?>

    <?= $form->field($model, 'street_id')
        ->widget(Kladr::class, [
            'type' => Kladr::TYPE_STREET,
            'options' => [
                'value' => $model->street,
                'placeHolder' => $model->getAttributeLabel('street'),
                'class' => 'form-control',
            ]
        ])
        ->label($model->getAttributeLabel('street'));
    ?>

    <?= $form->field($model, 'building_id')
        ->widget(Kladr::class, [
            'type' => Kladr::TYPE_BUILDING,
            'options' => [
                'value' => $model->building,
                'placeHolder' => $model->getAttributeLabel('building'),
                'class' => 'form-control',
            ]
        ])
        ->label($model->getAttributeLabel('building'));
    ?>

    <?= $form->field($model, 'zip_code')
        ->widget(Kladr::class, [
            'type' => Kladr::TYPE_ZIP,
            'options' => [
                'value' => $model->zip_code,
                'placeHolder' => $model->getAttributeLabel('zip_code'),
                'class' => 'form-control',
            ]
        ])
        ->label($model->getAttributeLabel('zip_code'));
    ?>

    <?= $form->field($model, 'appartment')
        ->textInput(['maxlength' => true])
        ->label($model->getAttributeLabel('appartment'));
    ?>

    <?= $form->field($model, 'arbitrary_address')->textInput(['maxlength' => true]) ?>

    <?= Yii::t('app', 'Deleted at: ') . $model->deleted_at ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
