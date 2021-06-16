<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Url;
use yii\helpers\Html;

\backend\assets\SetupAsset::register($this);

//$bundle = yiister\gentelella\assets\Asset::register($this);
//$this->registerCssFile(Url::to('/css/style.css'));
//$this->registerJsFile(Url::to('/js/common.js'), ['depends' => 'yii\web\JqueryAsset']);

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
                    <a href="<?= Url::to(['/']) ?>" class="site_title"
                       title="<?= Html::encode(Yii::t('app', 'Home')) ?>"><i class="logo"></i>
                        <span><?= Html::encode(Yii::$app->name) ?></span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <i class="fa fa-user-circle-o profile-img-icon"></i>
                    </div>
                    <div class="profile_info">
                        <?php /*<span>&nbsp;</span>*/ ?>
                        <h2><?= Html::encode(Yii::$app->user->identity->username ?? '') ?></h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>&nbsp;</h3>
                        <?php
                        $menuItems = [
                            [
                                'label' => Yii::t('app', 'Manual'),
                                'url' => ['/setup'],
                                'icon' => 'question',
                            ],
                            [
                                'label' => Yii::t('app', 'Chosen components'),
                                'url' => ['/setup/customer-active-component'],
                                'icon' => 'shopping-basket',
                            ],
                            //TODO: uncomment отдельная-пиццерия
                            /*[
                                'label' => Yii::t('app', 'Components'),
                                'url' => ['/setup/component'],
                                'icon' => 'shopping-basket',
                            ],
                            [
                                'label' => Yii::t('app', 'Link to embed'),
                                'url' => ['/setup/default/link-to-embed'],
                                'icon' => 'external-link',
                                'options' => [
                                    'class' => 'text-red-menu',
                                ]
                            ],*/
                            [
                                'label' => Yii::t('app', 'Co-workers'),
                                'url' => ['/setup/co-worker'],
                                'icon' => 'users',
                            ],
                            [
                                'label' => Yii::t('app', 'User'),
                                'icon' => 'user-o',
                                'url' => '#',
                                'template' => '<a><i class="fa fa-user-o"></i><span>{label}</span><span class="fa fa-chevron-down"></span></a>',
                                'items' => [
                                    ['label' => Yii::t('app', 'Account'), 'url' => ['/user/settings/account']],
                                    ['label' => Yii::t('app', 'Profile'), 'url' => ['/user/settings/profile']],
                                ],
                            ],
                            [
                                'label' => Yii::t('app', 'Orders'),
                                'icon' => 'handshake-o',
                                'url' => '#',
                                'template' => '<a><i class="fa fa-handshake-o"></i><span>{label}</span><span class="fa fa-chevron-down"></span></a>',
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Order list'),
                                        'url' => ['/setup/shop-order/index', 'sort' => '-created_at'],
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Reasons for rejection'),
                                        'url' => ['/setup/co-worker-decline-cause']
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Signal Contacts'),
                                        'url' => ['/setup/shop-order-signal']
                                    ],
                                ],
                            ],
                            [
                                'label' => Yii::t('app', 'Виртуализация'),
                                'icon' => 'handshake-o',
                                'url' => '#',
                                //'template' => '<a><i class="fa fa-handshake-o"></i><span>{label}</span><span class="fa fa-chevron-down"></span></a>',
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Виртуальные продавцы'),
                                        'url' => ['/setup/user-virtual'],
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Виртуальные покупатели'),
                                        'url' => ['/admin/customer-virtual'],
                                    ],
                                ],
                            ],
                        ];
                        if (Yii::$app->user->identity->isAdmin ?? false) {
                            $menuItems[] = [
                                'label' => Yii::t('app', 'Admin'),
                                'url' => '/admin',
                                'icon' => 'gear',
                            ];
                        }
                        echo \yiister\gentelella\widgets\Menu::widget(
                            [
                                'items' => $menuItems,
                            ]
                        )
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->

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
                                echo Html::encode(Yii::$app->user->identity->username ?? '');
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

                        <li class="nav-separator"></li>

                        <li>
                            <a href="<?= Url::to(['/site/contact']) ?>"
                               title="<?= Yii::t('app', 'Send us a message') ?>">
                                <i class="fa fa-envelope-o"></i>
                            </a>
                        </li>

                        <li class="nav-separator"></li>

                        <?php /* ?>
                        <li>
                            <a href="/vendor/order/<?= (Yii::$app->user->identity ? Yii::$app->user->identity->getOrderUid() : '') ?>" target="_blank" class="btn-show-order-form-new-window"
                               title="<?= Yii::t('app', 'Show order form (in new window)') ?>">
                                <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="btn-show-order-form" data-toggle="modal" data-target="#orderForm"
                               title="<?= Yii::t('app', 'Show order form (in popup)') ?>">
                                <i class="fa fa-caret-square-o-up"></i>
                            </a>
                        </li>
                        <?php */ ?>

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
                /*    'itemTemplate' => "<li><i>{link}</i></li>\n",
                    'homeLink' => [
                        'label' => 'Главная ',
                        'url' => Yii::$app->homeUrl,
                        'title' => 'Первая страница сайта мастеров по ремонту квартир',
                    ],*/
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                /*'options' => ['class' => 'breadcrumb', 'style' => ''],*/
            ]);
            ?>

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
<?php  /* ?>
<div id="orderForm" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Order form') ?></h4>
            </div><?php
            ?>
            <iframe class="modal-body" src="/vendor/order/<?= (Yii::$app->user->identity ? Yii::$app->user->identity->getOrderUid() : '') ?>"></iframe><?php
            ?>
        </div>

    </div>
</div>
<?php  */ ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
