<?php

/* @var $this yii\web\View */
/* @var $model frontend\modules\vendor\models\ShopOrderForm */

/* @var $uid string */

/* @var $activeUsers common\models\db\User[] */

/* @var $components common\models\db\Component[] */

/* @var $componentSets common\models\db\ComponentSet[] */


use common\models\html\ComponentHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use corpsepk\DaData\SuggestionsWidget;

$this->getAssetManager()->appendTimestamp = true;

$components = $components ?? [];

$this->title = Yii::t('app', 'Main page');

$this->registerCss(<<<CSS
body {
    height: 100%;
}
CSS
);

$initialJSCode = '';

$preloadedVideos = '';
foreach ($components as $comp) {
    if (isset($comp->componentVideos[0])) {
        $video = Url::to(Yii::$app->params['component_videos']['url_path'] . $comp->componentVideos[0]->relative_path);
        $preloadedVideos .= "url($video) ";
    }

    if (!empty($comp->item_select_min)) {
        for ($i = 0; $i < $comp->item_select_min; ++$i) {
            $initialJSCode .= 'addComponent($(".component-link[data-id=' . $comp->id . ']"));' . "\n";
        }
    }
}

if ($preloadedVideos) {
    $preloadedVideos = rtrim($preloadedVideos);
    $this->registerCss(<<<CSS
body:after{
    position:absolute; width:0; height:0; overflow:hidden; z-index:-1;
    content: $preloadedVideos;
}
CSS
    );
}

$this->registerJs(<<<JS
var logRegModalElem = $('#logRegModal');
logRegModalElem.on('shown.bs.modal', function() {
    logRegModalElem.find('.frame-content').html('<iframe class="frame-fill" src="/user/login" onload="gl.functions.resizeIframeHeight(this)"></iframe>');
});

/*gl.functions.setLogged = function() {
    location.reload(); 
};*/

JS
);

echo $this->render('_content_js', ['initialJSCode' => $initialJSCode, 'uid' => $uid, 'activeUsers' => $activeUsers]);

?>
<div class="vendor-panel">

    <div class="btn-order-container">
        <button type="submit" class="btn-order">
            <img class="oven-order" alt="<? /*= Yii::t('app', 'Order') */ ?>" src="/img/order-btn-sm.png">
            <div class="flame-wrapper">
                <img class="oven-flame" alt="<? /*= Yii::t('app', 'Flame') */ ?>" src="/img/flame.gif">
            </div>
        </button>
    </div>

    <section class="components-in-stock">

        <?php if ($components): ?>

            <?php \nsept\jscrollpane\JScrollPaneWidget::widget([
                'container' => '.vertical-pane',
                'mousewheel' => true,
                'settings' => [
                    // Plugin options (http://jscrollpane.kelvinluck.com/settings.html)
                    'scrollbarWidth' => 18,
                    'showArrows' => true,
                ]
            ]); ?>

            <div class="vertical-pane" style="overflow:auto">
                <?php
                //$componentItems = [];
                foreach ($components as $comp) {
                    $compStyle = '';
                    if (!empty($comp->item_select_min)) {
                        $compStyle = 'style="display:none"';
                    }

                    $item = "<div class='component' data-id='" . $comp->id . "' $compStyle>";

                    $html = '<div class="img-wrap" style="background-image: url(' . $comp->getImageUrl() . ')"></div>';
                    $html .= '<div class="short-name" title="' . Html::encode($comp->short_description) . '">' . Html::encode($comp->short_name) . '</div>'
                        /*. '<div class="price-discount" title="' . Html::encode(Yii::t('app',
                            'Price without discount')) . '">' . (!empty($comp->price_discount) ? Html::encode($comp->price_discount . ' руб.') : '') . '</div>'*/
                        . '<div class="price">' . ComponentHtml::getPriceCaption($comp) . '</div>';

                    $item .= Html::a($html, '#', array_merge($comp->createHtmlDataParams(), [
                        'class' => 'component-link',
                    ]));

                    $item .= '</div>';

                    //$componentItems[] = $item;
                    echo $item;
                } ?>
            </div>
        <?php else: ?>
            <?= Yii::t('app', 'There are no components! Please refer to “{url}” to add.',
                //['url' => Url::to(['/setup/component'], true)])
                [
                    'url' => Html::a(Yii::t('app', 'Components'), Url::to(['/setup/component'], true),
                        ['target' => '_blank'])
                ])
            ?>
        <?php endif; ?>

    </section>

    <div id="order-form">

        <div class="sidebar">

            <?php /*Slick::widget([

            // HTML tag for container. Div is default.
            'itemContainer' => 'div',

            // HTML attributes for widget container
            'containerOptions' => ['class' => 'slick-container'],

            // Position for inclusion js-code
            // see more here: http://www.yiiframework.com/doc-2.0/yii-web-view.html#registerJs()-detail
            'jsPosition' => yii\web\View::POS_READY,

            // It possible to use Slick.js events
            // see more: http://kenwheeler.github.io/slick/#events
//            'events' => [
//                'edge' => 'function(event, slick, direction) {
//                           console.log(direction);
//                           // left
//                      });'
//            ],

            // Items for carousel. Empty array not allowed, exception will be throw, if empty
            'items' => [
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/1.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/2.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/3.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/4.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/5.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/6.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/7.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/8.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/9.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/10.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/11.jpg)"></div>',
                '<div class="slick-pane-img" style="background-image:url(/img/slick/full-images/12.jpg)"></div>',
            ],

            // HTML attribute for every carousel item
            'itemOptions' => ['class' => 'cat-image'],

            // settings for js plugin
            // @see http://kenwheeler.github.io/slick/#settings
            'clientOptions' => [
                'autoplay' => true,
                'dots' => false,
                'infinite' => true,
                'slidesToShow' => 1,
                'slidesToScroll' => 1,
                'arrows' => false,
                'accessibility' => false,
                'touchMove' => false,
                'pauseOnHover' => false,
                'pauseOnFocus' => false,
                'draggable' => false,
                'fade' => true,
                'autoplaySpeed' => 9000,
                // note, that for params passing function you should use JsExpression object
                // but pay atention, In slick 1.4, callback methods have been deprecated and replaced with events.
                //'onAfterChange' => new JsExpression('function() {console.log("The cat has shown")}'),
            ],

        ]);*/ ?>

            <div class="slick-container">
                <div class="video">
                    <!--<div id="video"></div>-->
                </div>
                <div class="video-overlay"></div>
            </div>

            <div class="menu">
                <!--<img class="brick-wall" alt="" src="/img/brick-wall-120.png">-->
                <div class="create-pizza"><?= Yii::t('app', 'Create your pizza') ?></div>
                <div class="pizza-name"><?= Yii::t('app', 'Custom pizza') ?></div>
            </div>

            <!--<div class="resulting-panel"></div>-->
            <!--<div class="capt-price"><? /*= Yii::t('app', 'Price:') */ ?>
            <div class="total-price" data-total_price="0">&nbsp;</div>
        </div>-->
            <!--<div class="components-selected" style="display: none"></div>--><?php /*//TODO: реализовать потом */ ?>
            <div class="components-selected-details">
                <div class="panel-detail">

                    <div class="registration-info">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <div class="caption">
                                <?= Html::encode(Yii::t('app', 'You are not logged in.')) ?>
                            </div>
                            <i data-toggle="modal" data-target="#logRegModal" class="icon icon-enter"
                               title="<?= Html::encode(Yii::t('app', 'Sign In/Sign Up')) ?>"></i>
                        <?php else: ?>
                            <div class="caption">
                                <?= Html::encode(Yii::$app->user->identity->username) ?>
                            </div>

                            <?= Html::beginForm(['/user/security/logout'], 'post',
                            ['id' => 'custom-menu-logout', 'style' => 'visibility: collapse']) . Html::endForm() ?>
                            <i class="icon men-el exit"
                               title="<?= Yii::t('app', 'Sign Out') ?>"><a
                                        class="icon-exit"
                                        href="#"
                                        onclick="javascript:$('#custom-menu-logout').submit();return false;"></a></i>
                        <?php endif; ?>
                    </div>

                    <div class="no-components-pane">
                        <div class="ingredients"><?= Yii::t('app', 'Move components right here') ?></div>
                        <?= Html::img('/img/arrow-down.png', ['class' => 'arrow-down']) ?>
                    </div>

                    <div class="capt-price">
                        <div class="caption"><?= Yii::t('app', 'Total:') ?></div>
                        <div class="total-price" data-total_price="0">&nbsp;</div>
                    </div>

                    <div class="component-holder"></div>

                </div>
            </div>
        </div>

    </div>

    <!--<div class="video">
        <div id="video"></div>
    </div>-->

    <div class="standard-pizzas-panel">
        <div class="btn-head"><?= Yii::t('app', 'Pizzas') ?></div>
        <div class="pizzas-list">
            <div class="header"><?= Yii::t('app', 'Choose your pizza') ?></div>
            <?php
            foreach ($componentSets as $cs) {
                if ($cs->components) {
                    $compsData = [];
                    foreach ($cs->components as $comp) {
                        $compsData[] = $comp->createHtmlDataParams();
                    }
                    echo Html::tag('div', $cs->name, [
                        'class' => 'elem',
                        'data-id' => $cs->id,
                        'data-name' => $cs->name,
                        'data-components' => json_encode($compsData),
                    ]);
                }
            }
            ?>
        </div>
    </div>
</div>

<div id="switch-component-modal" class="switch-component-modal modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Please switch component') ?></h4>
            </div><?php
            ?>
            <div class="modal-body">
                <div class="component-container">
                    <?php
                    $selectedElemClass = 'selected';
                    foreach ($components as $comp) {
                        if ($comp->unit_switch_group) {
                            echo Html::beginTag('div', array_merge($comp->createHtmlDataParams(),
                                    ['class' => "switch-component $selectedElemClass",]))
                                . '<div class="image" style="background-image: url(' . Html::encode($comp->getImageUrl()) . ')"></div>'
                                . '<div class="name">' . Html::encode($comp->name) . '</div>'
                                . '<div class="price">' . ComponentHtml::getPriceCaption($comp) . '</div>'
                                . Html::endTag('div');

                            $selectedElemClass = '';
                        }
                    }
                    ?>
                </div>
            </div><?php
            ?>
        </div>

    </div>
</div>

<div id="popup-order-form" class="switch-component-modal modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Set up your order') ?></h4>
            </div>
            <div class="modal-body">
                <iframe class="popup-iframe" id="frame-order-form"
                        src="<?= Url::to(['/vendor/order-panel', 'uid' => $uid]) ?>"></iframe>
                <!--<iframe id="frame-order-form" src=""></iframe>-->
            </div>
        </div>

    </div>
</div>

<div id="popup-compose-form" class="switch-component-modal modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Confirm your order') ?></h4>
            </div>
            <div class="modal-body">
                <iframe id="frm-confirmed-order" src="/vendor/empty"></iframe>

                <div id="order-form-submit-wrapper">
                    <?php $form = ActiveForm::begin(
                        [
                            'id' => 'order-form-submit',
                            'action' => Url::to(['/vendor/order-create']),
                        ]
                    ); ?>

                    <input type="hidden" name="user_uid" value="<?= Html::encode($uid) ?>">

                    <div style="display: none">
                        <?= SuggestionsWidget::widget([
                            'name' => 'address',
                            'type' => SuggestionsWidget::TYPE_ADDRESS,
                            'token' => '3b0831fece6038806811a6eaef5843755d0ae9a4',
                        ]) ?>
                    </div>

                    <div class="order-data-container has-background info-panel"><?= Yii::t('app',
                            'Check the data and confirm the order.') ?><br>
                        <div class="info-message"><?= Yii::t('app',
                                'Заказ будет принят ближайшей пиццерией, о чём вам придет уведомление.') ?></div>
                    </div>

                    <div class="component-container"></div>

                    <button type="submit" class="btn-submit-order"><?= Yii::t('app', 'Confirm order') ?></button>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="popup-pizzeria-info" class="switch-component-modal modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Pizzeria info') ?></h4>
            </div>
            <div class="modal-body">
                <iframe class="popup-iframe" id="frame-pizzeria-info" src=""></iframe>
            </div>
        </div>

    </div>
</div>

<div id="logRegModal" class="switch-component-modal modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Вход в систему') ?></h4>
            </div>
            <div class="modal-body frame-content">
                <!--<iframe class="frame-fill" src=""></iframe>-->
            </div>
        </div>

    </div>
</div>
