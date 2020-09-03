<?php

Yii::setAlias('@root', dirname(dirname(__DIR__)));

$params = require __DIR__ . '/params.php';

$configData = [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cacheSse' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
        ],
        /*'view' => [
            'as YandexMetrika' => [
                'class' => \hiqdev\yii2\YandexMetrika\Behavior::class,
                'builder' => [
                    'class' => \hiqdev\yii2\YandexMetrika\CodeBuilder::class,
                    'id' => $params['yandexMetrika.id'],
                    'params' => $params['yandexMetrika.params'],
                ],
            ],
        ],*/
        /*'sse' => [
            'class' => \odannyc\Yii2SSE\LibSSE::class,
            'sleep_time' => 7,
            'exec_limit' => 0,    //30, //???  0?   // A 30 second time limit can prevent running out of resources quickly. - send.php
            'keep_alive_time' => 17,
        ],*/
    ],
];

if (!\common\helpers\Web::isLocal()) {
    $configData['components']['view']['as YandexMetrika'] = [
        'class' => \hiqdev\yii2\YandexMetrika\Behavior::class,
        'builder' => [
            'class' => \hiqdev\yii2\YandexMetrika\CodeBuilder::class,
            'id' => $params['yandexMetrika.id'],
            'params' => $params['yandexMetrika.params'],
        ],
    ];
}

return $configData;
