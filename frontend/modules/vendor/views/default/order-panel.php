<?php

/* @var $this yii\web\View */

/* @var $uid string */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

ActiveForm::begin(
    [
        'id' => 'order-form',
        //'action' => Url::to(['/vendor/order-create']),
        'action' => Url::to(['/vendor/order-compose']),
        'options' => [
            'style' => 'visibility:hidden',
        ],
    ]
);

ActiveForm::end();