<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}

AppAsset::register($this);

$this->registerJs(<<<JS
    var elems = {
        '#right-wrapper': $('#right-wrapper'),
        '#left-wrapper': $('#left-wrapper'),
        '#pane-setup': $('#pane-setup'),
        '#pane-order-make': $('#pane-order-make'),
        '#pane-order-watch': $('#pane-order-watch'),
        '#pane-welcome': $('#pane-welcome'),
        '.view-pane': $('.view-pane'),
        '.switch-elem': $('.switch-elem')
    };

    $('#pane-order-make .btn-wrap-order-make-form').click(function() {
      elems['#right-wrapper'].toggleClass('unwrapped');
      $(this).toggleClass('unwrapped');
    });
    
    elems['#pane-setup'].find('.btn-wrap-setup-form').click(function() {
        var btn = $(this);
        if (btn.hasClass('unwrapped')) {
            if (!elems['#pane-welcome'].hasClass('wrapped')) {
                elems['#pane-welcome'].addClass('wrapped');
            } else {
                btn.removeClass('unwrapped');
                elems['#pane-welcome'].removeClass('wrapped');
                elems['#pane-setup'].removeClass('unwrapped');
            }
        } else {
            elems['#pane-setup'].addClass('unwrapped');
            btn.addClass('unwrapped');
        }
    });
    
    elems['#pane-order-watch'].find('.btn-wrap-order-watch-form').click(function() {
      elems['#pane-order-watch'].toggleClass('unwrapped');
      $(this).toggleClass('unwrapped');
    });
    
    $('.switch-elem').click(function() {
        var currElem = $(this);
        
        elems['.switch-elem'].removeClass('selected');
        currElem.addClass('selected');
        
        var paneType = currElem.data('type');
        if (paneType =='#pane-welcome' || paneType =='#pane-setup') {
            elems['#right-wrapper'].hide();
            elems['#left-wrapper'].show();
        } else {
            elems['#left-wrapper'].hide();
            elems['#right-wrapper'].show();
        }
        elems['.view-pane'].hide();
        if (paneType == '#pane-welcome') {
            window.history.pushState(null, '', '/');
        } else {
            window.history.pushState(null, '', '/?path=' + paneType.substr(1));
        }
        if (paneType == '#pane-order-watch') {
            elems[paneType].find('iframe').attr( 'src', function ( i, val ) { return val; });
        }
        elems[paneType].show();
    });
    
JS
);

if ($currentPane = Yii::$app->request->get('path')) {
    $currentPaneJs = json_encode($currentPane);
    $this->registerJs(<<<JS
        $('.switch-elem[data-type="#' + $currentPaneJs + '"]').trigger('click');
JS
    );
}

/*if (Yii::$app->user->isGuest) {
    $this->registerCss(<<<CSS
#wrap {
    padding-top: 0 !important;
}
CSS
    );
}*/

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="wrap">
    <?php if (!Yii::$app->user->isGuest): ?>
        <menu class="pane-mobile-switcher">
            <div class="switch-elem<?= $currentPane == null ? ' selected' : '' ?>" data-type="#pane-welcome">
                <div class="icon-home3 icon"></div>
                <?= Yii::t('app', 'Главная') ?>
            </div>
            <div class="switch-elem<?= $currentPane == 'pane-setup' ? ' selected' : '' ?>" data-type="#pane-setup">
                <div class="icon-wrench icon"></div>
                <?= Yii::t('app', 'Настройки') ?>
            </div>
            <div class="switch-elem<?= $currentPane == 'pane-order-make' ? ' selected' : '' ?>"
                 data-type="#pane-order-make">
                <div class="icon-hammer icon"></div>
                <?= Yii::t('app', 'Конструктор') ?>
            </div>
            <div class="switch-elem<?= $currentPane == 'pane-order-watch' ? ' selected' : '' ?>"
                 data-type="#pane-order-watch">
                <div class="icon-cart icon"></div>
                <?= Yii::t('app', 'Заказы') ?>
            </div>
        </menu>

        <?php /* $this->render('_menu')*/ ?>
    <?php endif; ?>
    <div id="wrap-inner">
        <div id="left-wrapper">
            <main id="pane-welcome" class="view-pane">
                <button style="display: none" class="pane-switch" title="Развернуть панель"></button>
                <?= $this->render('_nav_v_2') ?>

                <?= \common\widgets\Alert::widget() ?>

                <?= $content ?>

                <?= $this->render('_footer_v_2') ?>
            </main>
            <?php if (!Yii::$app->user->isGuest): ?>
                <section id="pane-setup" class="view-pane">
                    <iframe class="total-frame" src="<?= Url::to(['/setup'], true) ?>"></iframe>
                    <i class="pane-switch icon-arrow-up-circle btn-wrap-setup-form" title="Развернуть панель"></i>
                </section>
            <?php endif; ?>
        </div>
        <?php if (!Yii::$app->user->isGuest): ?>
            <div id="right-wrapper">
                <section id="pane-order-make" class="view-pane">
                    <!--<iframe class="total-frame"
                            src="<?/*= Url::to(['/vendor/order']) */?>/<?/*= Yii::$app->user->identity->getOrderUid() */?>"></iframe>-->
                    <iframe class="total-frame" src="<?= Yii::$app->params['domain-customer-schema'] ?>://<?= Yii::$app->params['domain-customer'] ?>"></iframe>
                    <i class="pane-switch icon-arrow-left-circle btn-wrap-order-make-form"
                       title="Развернуть панель"></i>
                </section>
                <section id="pane-order-watch" class="view-pane">
                    <iframe class="total-frame" src="<?= Url::to(['/setup/shop-order/index']) ?>"></iframe>
                    <i class="pane-switch icon-arrow-up-circle btn-wrap-order-watch-form" title="Развернуть панель"></i>
                </section>
            </div>
        <?php endif; ?>
    </div>
</div>

<!--<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <? /*= date('Y') */ ?></p>

        <p class="pull-right"><? /*= Yii::powered() */ ?></p>
    </div>
</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
