<?php

$paramsLocal = require __DIR__ . '/params_local.php';

return array_merge([
    'adminEmail' => 'TwilightTower@mail.ru',
    'supportEmail' => 'TwilightTower@mail.ru',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,


    // @see https://ipstack.com
    'ipstack_access_key' => 'd95b72fb4f044d0c9c9ed6eb35571aa3',

    'map_default_places_by_language' => [
        // Moscow is default
        'ru-RU' => [
            'latitude' => '55.580748',
            'longitude' => '36.8251138',
            'zoom' => 5,
        ],
        // Default is Netherland (Nederland on map?)
        'not_set' => [
            'latitude' => '52.3545828',
            'longitude' => '4.763877',
            'zoom' => 4,
        ],
    ],

    // Изображения компонентов
    'component_images' => [
        'url_path' => '/img/compo/',
    ],

    // Видео (gif), касающиеся компонентов
    'component_videos' => [
        'url_path' => '/video/construct/',
    ],

    // Изображение пользова
    'customer_images' => [
        'url_path' => '/img/cust/',
    ],

    /**
     * см. dadata.ru
     */
    'dadata' => [
        'api-key' => '3b0831fece6038806811a6eaef5843755d0ae9a4',
        'standardisation-secret-key'
    ],

], $paramsLocal);
