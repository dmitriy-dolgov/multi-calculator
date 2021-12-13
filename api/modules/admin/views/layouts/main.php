<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yiister\gentelella\widgets\Menu;

\api\assets\AdminAsset::register($this);

if ($this->context->module->id == 'user' && $this->context->id == 'admin' && $this->context->action->id == 'index') {
    $this->registerCss(<<<CSS
.grid-view th {
    white-space: normal;
}
CSS
    );
}

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>">
<?php $this->beginBody(); ?>
<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="<?= Url::to(['/']) ?>" class="site_title"><i class="logo"></i>
                        <span><?= Html::encode(Yii::$app->name) ?></span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile clearfix">
                    <div class="profile_pic">
                        <i class="fa fa-user-circle-o profile-img-icon"></i>
                    </div>
                    <div class="profile_info">
                        <?php /*<span>&nbsp;</span>*/ ?>
                        <h2><?= Html::encode(Yii::$app->user->identity->username) ?></h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br/>
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3><?= Yii::t('app', 'Common Items') ?></h3>
                        <?= Menu::widget(
                            [
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Geopoints'),
                                        'url' => ['/admin/geopoint'],
                                        'icon' => 'map-marker',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Categories'),
                                        'url' => ['/admin/category'],
                                        'icon' => 'code-fork',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Component sets'),
                                        'url' => ['/admin/component-set'],
                                        'icon' => 'sitemap',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Components'),
                                        'url' => ['/admin/component'],
                                        'icon' => 'shopping-basket',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Units'),
                                        'url' => '/admin/unit',
                                        'icon' => 'balance-scale',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Switching groups'),
                                        'url' => '/admin/component-switch-group',
                                        'icon' => 'toggle-on',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Co-worker functions'),
                                        'url' => '/admin/co-worker-function',
                                        'icon' => 'vcard-o',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Texts'),
                                        'url' => '/admin/texts',
                                        'icon' => 'file-text-o',
                                    ],

                                    [
                                        'label' => Yii::t('app', 'Карта доставки'),
                                        'url' => '#',
                                        'template' => '<a><i class="fa fa-map-o"></i><span>{label}</span><span class="fa fa-chevron-down"></span></a>',
                                        //'icon' => 'map-o',
                                        'items' => [
                                            [
                                                'label' => Yii::t('app', 'Изображения курьера'),
                                                'url' => ['/admin/courier-images'],
                                            ],
                                            [
                                                'label' => Yii::t('app', 'Данные курьера'),
                                                'url' => ['/admin/courier-data'],
                                            ],
                                        ],
                                    ],
                                ],
                            ]
                        )
                        ?>
                    </div>

                    <div class="menu_section">
                        <h3><?= Yii::t('app', 'Monitoring') ?></h3>
                        <?= Menu::widget(
                            [
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Orders'),
                                        'url' => '/admin/order/list-user-worker-related',
                                        'icon' => 'handshake-o',
                                    ],
                                ],
                            ]
                        )
                        ?>
                    </div>

                    <div class="menu_section">
                        <h3><?= Yii::t('app', 'Management') ?></h3>
                        <?= Menu::widget(
                            [
                                'items' => [
                                    [
                                        'label' => Yii::t('usuario', 'Users'),
                                        'url' => '/user/admin/index',
                                        'icon' => 'users',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Шаблоны пользователей'),
                                        'url' => '/user/admin/index',
                                        'icon' => 'user-plus',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'RBAC'),
                                        'url' => '#',
                                        'template' => '<a><i class="fa fa-vcard"></i><span>{label}</span><span class="fa fa-chevron-down"></span></a>',
                                        'icon' => 'vcard',
                                        'items' => [
                                            ['label' => Yii::t('usuario', 'Roles'), 'url' => ['/user/role/index']],
                                            [
                                                'label' => Yii::t('usuario', 'Permissions'),
                                                'url' => ['/user/permission/index']
                                            ],
                                            ['label' => Yii::t('usuario', 'Rules'), 'url' => ['/user/rule/index']],
                                        ],
                                    ],
                                ],
                            ]
                        )
                        ?>
                    </div>

                    <div class="menu_section">
                        <h3><?= Yii::t('app', 'Виртуализация') ?></h3>
                        <?= Menu::widget(
                            [
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Виртуальные продавцы'),
                                        'url' => '/admin/user-virtual',
                                        'icon' => 'home',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Виртуальные покупатели'),
                                        'url' => ['/admin/customer-virtual'],
                                        'icon' => 'money',
                                    ],
                                ],
                            ]
                        )
                        ?>
                    </div>

                    <div class="menu_section">
                        <h3><?= Yii::t('app', 'Other') ?></h3>
                        <?= Menu::widget(
                            [
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Профили истории'),
                                        'url' => '/admin/history-profile',
                                        'icon' => 'history',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Archive'),
                                        'url' => '/admin/archive',
                                        'icon' => 'archive',
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Set Up'),
                                        'url' => '/setup',
                                        'icon' => 'wrench',
                                    ],
                                ],
                            ]
                        )
                        ?>
                    </div>

                </div>


                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a href="https://twitter.com" target="_blank" data-toggle="tooltip" data-placement="top"
                       title="Twitter">
                        <span class="fa fa-twitter" aria-hidden="true"></span>
                    </a>
                    <a href="https://facebook.com" target="_blank" data-toggle="tooltip" data-placement="top"
                       title="Facebook">
                        <span class="fa fa-facebook" aria-hidden="true"></span>
                    </a>
                    <a href="https://vk.com" target="_blank" data-toggle="tooltip" data-placement="top" title="VK">
                        <span class="fa fa-vk" aria-hidden="true"></span>
                    </a>
                    <a href="https://youtube.com" target="_blank" data-toggle="tooltip" data-placement="top"
                       title="Youtube">
                        <span class="fa fa-youtube" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->

            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa fa-user-circle profile-img-icon"></i>
                                <?php
                                //TODO: реализовать изображение
                                //<img src="http://placehold.it/128x128" alt="">
                                echo Html::encode(Yii::$app->user->identity->username);
                                ?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li>
                                    <a href="<?= Url::to(['/user/settings/account']) ?>">
                                        <?php
                                        //TODO: можно реализовать степень заполненности
                                        //<span class="badge bg-red pull-right">50%</span>
                                        ?>
                                        <span><?= Yii::t('app', 'Account') ?></span>
                                    </a>
                                </li>
                                <li><a href="<?= Url::to(['/user/settings/profile']) ?>"> <?= Yii::t('app',
                                            'Profile') ?></a>
                                </li>
                                <?php /*<li>
                                    <a href="javascript:;">Help</a>
                                </li>*/ ?>
                                <li>
                                    <hr>
                                </li>
                                <li>
                                    <?= Html::beginForm(['/user/security/logout'], 'post', ['id' => 'form-logout']) ?>
                                    <?= Html::endForm() ?>
                                    <a href="#" onclick="document.getElementById('form-logout').submit();return false;"><i
                                                class="fa fa-sign-out pull-right"></i> <?= Yii::t('app',
                                            'Sign Out') ?></a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left">
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>

            <?= \yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);
            ?>

            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $messages): ?>
                <?php foreach ((array)$messages as $message): ?>
                    <div class="alert alert-<?= $type ?>" role="alert">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?= $message ?>
                    </div>
                <?php endforeach ?>
            <?php endforeach ?>

            <?= $content ?>
        </div>
        <!-- /page content -->

        <footer>
            <?php $cpTxt = '&copy; ' . Html::encode(Yii::$app->name) . ' ' . date('Y'); ?>
            <div class="pull-right"><a href="/" alt="<?= $cpTxt ?>"><?= $cpTxt ?></a></div>
            <div class="clearfix"></div>
        </footer>
    </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<!-- /footer content -->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
