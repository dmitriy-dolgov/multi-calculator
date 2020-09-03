<?php

use yii\web\UserEvent;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'multipurpose-calculator-creator-app-backend',
    'name' => 'Pizza Maya',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'ru',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'mapHandler' => [
            'class' => 'common\components\MapHandler',
        ],
        /*'dbConnectionManager' => [
            'class' => 'common/components/DbConnectionManager',
        ],*/
        'user' => [
            'identityClass' => 'common\models\db\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'on ' . \yii\web\User::EVENT_AFTER_LOGIN => function (UserEvent $event) {
                Yii::$app->mapHandler->setupLoginLatLong($event->identity);
            },
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 0 : 0,
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['sse-order'],
                    'logFile' => '@runtime/logs/sse-order.log',
                    'exportInterval' => 1,
                    'levels' => ['trace'],
                    'logVars' => [],
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
            ],
        ],

        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        //'app/error' => 'error.php',
                    ],
                ],
                'app-alt-1' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'fileMap' => [
                        'app-alt-1' => 'app-alt-1.php',
                    ],
                ],
                'err' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'fileMap' => [
                        'err' => 'err.php',
                    ],
                ],
                'db' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
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
                    '@Da/User/resources/views' => '@backend/views/user',
                ]
            ]
        ],

    ],

    'modules' => [
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
            // other settings (refer documentation)
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // other module settings
        ],
        'setup' => [
            'class' => 'backend\modules\setup\Module',
        ],
        'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
        'treemanager' => [
            'class' => '\kartik\tree\Module',
            // other module settings, refer detailed documentation
        ],
        'user' => [
            'class' => \Da\User\Module::class,
            // ...other configs from here: [Configuration Options](installation/configuration-options.md), e.g.
            'administrators' => ['daiviz', 'Ladlen', 'Ruslan'], // this is required for accessing administrative actions
            //'generatePasswords' => true,
            //TODO: разобраться что это точно
            'switchIdentitySessionKey' => 'ladlen_daiviz_undula',

            'classMap' => [
                'User' => \common\models\db\User::class,
                'Profile' => \common\models\db\Profile::class,
                'ResendForm' => \common\models\forms\ResendForm::class,
            ],

            'layout' => '@backend/modules/setup/views/layouts/main',

            'controllerMap' => [
                'settings' => 'backend\modules\setup\controllers\SettingsController',
                'security' => [
                    'class' => \Da\User\Controller\SecurityController::class,
                    //'class' => 'app\controllers\SecurityController',
                    'layout' => '@backend/views/layouts/registration',
                ],
                'registration' => [
                    'class' => \Da\User\Controller\RegistrationController::class,
                    'layout' => '@backend/views/layouts/registration',
                    /*'on ' . \Da\User\Event\FormEvent::EVENT_AFTER_REGISTER => function (\Da\User\Event\FormEvent $event
                    ) {
                        //TODO: postponed; see folder components.postponed (separate databases for each user)
//                        if ($user = \Da\User\Model\User::findOne(['username' => $event->form->username])) {
//                            $databaseName = 'pizza_user_' . $user->getPrimaryKey();
//                        }
                    },*/
                    'on ' . \Da\User\Event\UserEvent::EVENT_AFTER_CONFIRMATION => function (
                        \Da\User\Event\UserEvent $event
                    ) {
                        //TODO: при ручной активации аккаунта админом - вызывать
                        $auth = \Yii::$app->authManager;
                        $clientRole = $auth->getRole('client');
                        $auth->assign($clientRole, $event->getUser()->getId());
                    },
                ],
                'admin' => [
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
                ],
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
