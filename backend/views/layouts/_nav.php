<?php

use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$navItems = [
    ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
    ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
    [
        'label' => Yii::t('app', 'Contact'),
        'url' => ['/site/contact'],
        'linkOptions' => ['title' => Yii::t('app', 'Send us a message')]
    ],
];

if (!Yii::$app->user->isGuest) {
    $navItems[] = ['label' => Yii::t('app', 'Set Up'), 'url' => ['/setup']];

    if (Yii::$app->user->identity->isAdmin) {
        $navItems[] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin']];
    }
}

if (Yii::$app->user->isGuest) {
    $navItems[] = ['label' => Yii::t('app', 'Sign In'), 'url' => ['/user/security/login']];
    $navItems[] = ['label' => Yii::t('app', 'Sign Up'), 'url' => ['/user/registration/register']];
} else {
    $navItems[] = '<li>'
        . Html::beginForm(['/user/security/logout'], 'post')
        . Html::submitButton(
            Yii::t('app', 'Sign Out') . ' (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
}

echo \yii\bootstrap\Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $navItems,
]);
NavBar::end();
