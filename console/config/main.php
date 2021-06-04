<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'user' => [
            'class' => 'common\models\db\User',
            //'identityClass' => 'common\models\db\User',
            //'enableAutoLogin' => true,
            //'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
    ],
    'modules' => [
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
                    //'class' => \Da\User\Controller\SecurityController::class,
                    'class' => 'common\controllers\SecurityController',
                    'layout' => '@frontend/views/layouts/registration',
                    'on ' . \Da\User\Event\FormEvent::EVENT_AFTER_LOGIN => function (\Da\User\Event\FormEvent $event) {
                        \Yii::$app->controller->redirect(['/site/signal-to-parent', 'result' => 'logged']);
                        \Yii::$app->end();
                    },
                    'on ' . \Da\User\Event\SocialNetworkAuthEvent::EVENT_AFTER_AUTHENTICATE => function ($event) {
                        \Yii::$app->controller->redirect(['/site/signal-to-parent-opener', 'result' => 'logged']);
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
    'params' => $params,
];
