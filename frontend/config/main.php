<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'multipurpose-calculator-creator-app-customer',
    'name' => 'Pizza Maya',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'ru',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'mapHandler' => [
            'class' => 'common\components\MapHandler',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'dbConnectionManager' => [
            'class' => 'common/components/DbConnectionManager',
        ],*/
        'user' => [
            'identityClass' => 'common\models\db\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //'/vendor/get-order-form' => '/vendor/default/get-order-form',
                /*'vendor/order/<uid:[\w_]+>' => 'vendor/default/order',
                'vendor/<action:[\w\-]+>/<id:(.*?)>' => 'vendor/default/<action>/<id>',
                'vendor/<action:[\w\-]+>' => 'vendor/default/<action>',*/
                /*'order/<uid:[\w_]+>' => 'site/order',
                '<action:[\w\-]+>/<id:(.*?)>' => 'site/<action>/<id>',
                '<action:[\w\-]+>' => 'site/<action>',*/
            ],
        ],

        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        //'app/error' => 'error.php',
                    ],
                ],
                'app-alt-1' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'fileMap' => [
                        'app-alt-1' => 'app-alt-1.php',
                    ],
                ],
                'err' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'fileMap' => [
                        'err' => 'err.php',
                    ],
                ],
                'db' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'fileMap' => [
                        'db' => 'db.php',
                    ],
                ],
            ],
        ],

        'authManager' => [
            'class' => 'Da\User\Component\AuthDbManagerComponent',
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@Da/User/resources/views' => '@frontend/views/user'
                ]
            ]
        ],

        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                /*'facebook' => [
                    'class' => 'Da\User\AuthClient\Facebook',
                    'clientId' => 'facebook_client_id',
                    'clientSecret' => 'facebook_client_secret',
                ],*/
                'vkontakte' => [
                    'class' => 'Da\User\AuthClient\VKontakte',
//                    'clientId' => '7543540',
//                    'clientSecret' => 'FjLhZ0rIej1LVmnBCAua',
                    'clientId' => '7543832',
                    'clientSecret' => 'XO3ltkl3J0CoZ95vAoPB',
                ],
            ],
        ],
    ],

    'modules' => [
        /*'vendor' => [
            'class' => 'frontend\modules\vendor\Module',
        ],*/
        /*'treemanager' => [
            'class' => '\kartik\tree\Module',
            // other module settings, refer detailed documentation
        ],*/
        'user' => [
            'class' => \Da\User\Module::class,
            //'generatePasswords' => true,
            //TODO: разобраться что это точно
            'switchIdentitySessionKey' => 'ladlen_daiviz_undula',

            'classMap' => [
                'User' => \common\models\db\User::class,
                'Profile' => \common\models\db\Profile::class,
                'ResendForm' => \common\models\forms\ResendForm::class,
            ],

            //'layout' => '@backend/modules/setup/views/layouts/main',

            'controllerMap' => [
                //'settings' => 'backend\modules\setup\controllers\SettingsController',
                'security' => [
                    'class' => \Da\User\Controller\SecurityController::class,
                    'layout' => '@frontend/views/layouts/registration',
                    'on ' . \Da\User\Event\FormEvent::EVENT_AFTER_LOGIN => function (\Da\User\Event\FormEvent $event) {
                        \Yii::$app->controller->redirect(['/signal-to-parent', 'result' => 'logged']);
                        \Yii::$app->end();
                    },
                ],
                'registration' => [
                    'class' => \Da\User\Controller\RegistrationController::class,
                    'layout' => '@frontend/views/layouts/registration',
                ],
                /*'admin' => [
                    'class' => \Da\User\Controller\AdminController::class,
                    'layout' => '@backend/modules/admin/views/layouts/main',
                ],
                'role' => [
                    'class' => \Da\User\Controller\RoleController::class,
                    'layout' => '@backend/modules/admin/views/layouts/main',
                ],
                'permission' => [
                    'class' => \Da\User\Controller\PermissionController::class,
                    'layout' => '@backend/modules/admin/views/layouts/main',
                ],
                'rule' => [
                    'class' => \Da\User\Controller\RuleController::class,
                    'layout' => '@backend/modules/admin/views/layouts/main',
                ],*/
            ],
        ],
    ],
    /*'container' => [
        'definitions' => [
            'corpsepk\DaData\SuggestionsWidget' => [
                'token' => '3b0831fece6038806811a6eaef5843755d0ae9a4',
            ],
        ],
    ],*/

    'params' => $params,
];
