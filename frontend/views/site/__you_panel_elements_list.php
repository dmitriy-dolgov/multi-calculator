<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

?>

<div class="you-panel-elements-list unwrapped-panel">
    <?= Html::img('/img/ok-btn.svg', [
            'title' => Yii::t('app', 'Свернуть'),
            'class' => 'unwrap-panel__close-button']
    ) ?>
    <div class="header">Ваши адреса:</div>
    <hr>
    <div class="unwrap-panel-content">
        Адрес: Ул. Джонни Деппа
        Тел: 8902342323
        Email: fkjdsl@rere.com
        <?= Html::img('/img/close-btn.svg', [
                'title' => Yii::t('app', 'Удалить адрес'),
                'class' => 'unwrap-panel__delete-button address']
        ) ?>
    </div>
    <div class="unwrap-panel-content">
        Адрес: Ул. Джонни Деппа 2
        Тел: 890234232355
        Email: fkjdsl@rere.com22
        <?= Html::img('/img/close-btn.svg', [
                'title' => Yii::t('app', 'Удалить адрес'),
                'class' => 'unwrap-panel__delete-button address']
        ) ?>
    </div>
    <div class="unwrap-panel-content">
        Адрес: Ул. Джонни Деппа 3
        Тел: 8902342323553
        Email: fkjdsl@rere.com2233
        <?= Html::img('/img/close-btn.svg', [
                'title' => Yii::t('app', 'Удалить адрес'),
                'class' => 'unwrap-panel__delete-button address']
        ) ?>
    </div>
</div>