<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

?>

<div class="orders-panel-elements-list unwrapped-panel">
    <?= Html::img('/img/ok-btn.svg', ['title' => Yii::t('app', 'Свернуть'), 'class' => 'unwrap-panel__close-button']) ?>
    <div class="header">Ваши заказы:</div>
    <hr>
    Нет заказов.
</div>