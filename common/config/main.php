<?php

Yii::setAlias('@root', dirname(dirname(__DIR__)));

$params = require __DIR__ . '/params.php';

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'as YandexMetrika' => [
                'class' => \hiqdev\yii2\YandexMetrika\Behavior::class,
                'builder' => [
                    'class' => \hiqdev\yii2\YandexMetrika\CodeBuilder::class,
                    'id' => $params['yandexMetrika.id'],
                    'params' => $params['yandexMetrika.params'],
                ],
            ],
        ],
    ],
];
