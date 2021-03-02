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
use kartik\checkbox\CheckboxX;
use common\models\db\Category;

$this->getAssetManager()->appendTimestamp = true;

$components = $components ?? [];

$this->title = Yii::t('app', 'Main page');

$this->registerCss(<<<CSS
body {
    height: 100%;
}

.contract-modal .modal-header {
    color: #333;
}
.contract-modal .modal-body {
    color: #2a2a2a;
}
#contractModal {
    padding-left: 0;
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

/*gl.functions.adjustComponentToSelectHeight();
$(window).resize(function() {
  gl.functions.adjustComponentToSelectHeight();
});*/

$('.components-in-stock .collapse-head').click(function() {
  var currentWrapComponent = $(this).next('.collapse-content');
  elems['.components-in-stock'].find('.collapse-content').each(function () {
      if (!$(this).is(currentWrapComponent)) {
          $(this).removeClass('unwrap');
      }
  });
  currentWrapComponent.toggleClass('unwrap');
  
  setTimeout(
    function () {
        elems['.vertical-pane'].data('jsp').reinitialise();
        //elems['.vertical-pane'].css('overflow', 'visible');
    }, 50
  );
});

gl.functions.showBsModal = function(type) {
    $('#' + type + 'Modal').modal('show');
}

JS
);

echo $this->render('_content_js', ['initialJSCode' => $initialJSCode, 'uid' => $uid, 'activeUsers' => $activeUsers]);

?>
<div class="vendor-panel">

    <div class="btn-order-container">
        <button type="submit" class="btn-order">
            <img class="oven-order" alt="<?= Yii::t('app', 'Order') ?>" src="/img/oven-russian.gif">
            <?php /* ?>
            <img class="oven-order" alt="<?= Yii::t('app', 'Order') ?>" src="/img/order-btn-sm.png">
            <div class="flame-wrapper">
                <img class="oven-flame" alt="<?= Yii::t('app', 'Flame') ?>" src="/img/flame-50.gif">
            </div>
            <?php */ ?>
        </button>
        <!--<div class="categories-panel btn-head"><?php /*= Yii::t('app', 'Catalog') */ ?></div>-->
    </div>

    <section class="components-in-stock">

        <!--<div class="catalog-name"><?php /*= Html::encode(Yii::t('app', 'Popular')) */ ?></div>-->

        <?php if ($components): ?>

            <?php /*\nsept\jscrollpane\JScrollPaneWidget::widget([
                'container' => '.vertical-pane',
                'mousewheel' => true,
                'settings' => [
                    // Plugin options (http://jscrollpane.kelvinluck.com/settings.html)
                    'scrollbarWidth' => 18,
                    'showArrows' => true,
                ]
            ]); */ ?>

            <div class="vertical-pane" style="overflow:auto">
                <?php
                $collapsedComponents = [];
                foreach ($components as $comp) {
                    $categoryId = $comp->category_id ?? 0;
                    if (empty($collapsedComponents[$comp->category_id])) {
                        if ($comp->category_id) {
                            //$categoryId = $comp->category_id;
                            if ($categoryObj = Category::findOne($categoryId)) {
                                $collapsedComponents[$categoryId] = [
                                    'name' => $categoryObj->short_name ?: $categoryObj->name,
                                    'elems' => [],
                                ];
                            } else {
                                Yii::error('No category with ID: ' . $categoryId);
                            }
                        } else {
                            $collapsedComponents[0] = [
                                'name' => Yii::t('app', 'Not in categories'),
                                'elems' => [],
                            ];
                        }
                    }
                    $collapsedComponents[$categoryId]['elems'][] = $comp;
                }

                foreach ($collapsedComponents as $key => $collElem) {
                    if (!$key) {
                        continue;
                    }
                    echo '<div class="collapse-head">' . Html::encode($collElem['name']) . '</div>'
                        . '<div class="collapse-content">';

                    foreach ($collElem['elems'] as $comp) {
                        $compStyle = '';
                        /*if (!empty($comp->item_select_min)) {
                            $compStyle = 'style="display:none"';
                        }*/

                        $item = "<div class='component' data-category_id='" . $comp->category_id . "' data-id='" . $comp->id . "' $compStyle>";

                        $html = '<div class="filler-wrapper">'
                            . '<img class="filler" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="">'
                            . '<div class="img-wrap" style="background-image: url(' . $comp->getImageUrl() . ')"></div>'
                            . '</div>'
                            . '<div class="filler-over">'
                            //. '<div class="img-wrap" style="background-image: url(' . $comp->getImageUrl() . ')"></div>'
                            . '<div class="comp-info">'
                            . '<div class="short-name" title="' . Html::encode($comp->short_description) . '">' . Html::encode($comp->short_name) . '</div>'
                            /*. '<div class="price-discount" title="' . Html::encode(Yii::t('app',
                                'Price without discount')) . '">' . (!empty($comp->price_discount) ? Html::encode($comp->price_discount . ' руб.') : '') . '</div>'*/
                            . '<div class="price">' . ComponentHtml::getPriceCaption($comp) . '</div>'
                            . '</div>'
                            . '</div>';

                        $item .= Html::a($html, '#', array_merge($comp->createHtmlDataParams(), [
                            'class' => 'component-link',
                        ]));

                        $item .= '</div>';

                        //$componentItems[] = $item;
                        echo $item;
                    }

                    echo '</div>';
                }
                ?>
            </div>

            <?php \nsept\jscrollpane\JScrollPaneWidget::widget([
                'container' => '.vertical-pane',
                'mousewheel' => true,
                'settings' => [
                    // Plugin options (http://jscrollpane.kelvinluck.com/settings.html)
                    'scrollbarWidth' => 18,
                    'showArrows' => true,
                    //'contentWidth' => false,
                    'contentWidth' => '0px',
                    //'alwaysShowVScroll' => true,
                    //'isScrollableH' => false,
                    //'isScrollableV' => true,
                    //'percentInViewH' => 0,
                ],
            ]); ?>
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

        <div class="menu-unwrap-panel folded">
            <div class="menu-unwrap-button"><?= Yii::t('app', 'Menu') ?></div>
            <div class="menu-unwrap-elems">
                <div class="menu-item pizzas"><?= Yii::t('app', 'Pizzas') ?></div>
                <div class="menu-item orders"><?= Yii::t('app', 'Orders') ?></div>
                <div class="menu-item you"><?= Yii::t('app', 'You') ?></div>
            </div>
        </div>

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
                <?php /* ?><div class="pizza-name"><?= Yii::t('app', 'Custom pizza') ?></div><?php */ ?>
            </div>

            <!--<div class="resulting-panel"></div>-->
            <!--<div class="capt-price"><?php /*= Yii::t('app', 'Price:') */ ?>
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

                    <div class="pizza-name"><?= Yii::t('app', 'Custom pizza') ?></div>

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

    <?php /* ?>
    <div class="video">
        <div id="video"></div>
    </div>
    <?php */ ?>

    <?php /* ?>
    <div class="categories-panel wrp-pane">
        <div class="btn-head"><?= Yii::t('app', 'Catalog') ?></div>
        <div class="panel-elements-list">
            <?php
            $categories = \common\models\db\Category::find()->all();
            foreach ($categories as $categ) {
                echo '<div class="elem">' . Html::encode($categ->name) . '</div>';
            }
            ?>
        </div>
    </div>
    <?php */ ?>

    <?php /* ?>
    <div class="categories-panel panel-elements-list">
        <div class="header"><?= Yii::t('app', 'Select categories') ?></div>
        <hr>
        <div class="elem">
            <?= CheckboxX::widget([
                'name' => 'ct-sel-all',
                'options' => [
                    'id' => 'ct-sel-all',
                    'class' => 'ct-sel-elem',
                ],
                'pluginOptions' => ['threeState' => false],
            ]) . '<label class="cbx-label" for="ct-sel-all">' . Yii::t('app', 'All') . '</label>'
            . CheckboxX::widget([
                'name' => 'ct-sel-popular',
                'options' => [
                    'id' => 'ct-sel-popular',
                    'class' => 'ct-sel-elem',
                ],
                'pluginOptions' => ['threeState' => false],
            ]) . '<label class="cbx-label" for="ct-sel-popular">' . Yii::t('app', 'Popular') . '</label>'
            ?>
        </div>
        <?php
        $categories = \common\models\db\Category::find()->all();
        foreach ($categories as $key => $categ) {
            echo '<div class="elem">' .
                CheckboxX::widget([
                    'name' => 'ct-sel-' . $key,
                    'value' => 1,
                    'options' => [
                        'id' => 'ct-sel-' . $key,
                        'class' => 'ct-sel-elem',
                        'data-category_id' => $categ->id,
                    ],
                    'pluginOptions' => ['threeState' => false],
                ]) . '<label class="cbx-label" for="ct-sel-' . $key . '">' . Html::encode($categ->name) . '</label></div>';
        }
        ?>
    </div>
    <?php */ ?>

    <div class="customer-orders-panel wrp-pane" style="display: none">
        <div class="btn-head"><?= Yii::t('app', 'Orders') ?></div>
        <div class="panel-elements-list">
            <div class="header"><?= Yii::t('app', 'Your active orders') ?></div>
            <hr>
            <div class="orders-container">
            </div>
        </div>
    </div>

<!--    <div class="menu-unwrap-panel">
        <div class="menu-unwrap-button"><?/*= Yii::t('app', 'Menu') */?></div>
        <div class="menu-unwrap-elems">
            <div><?/*= Yii::t('app', 'Pizzas') */?></div>
            <div><?/*= Yii::t('app', 'Orders') */?></div>
            <div><?/*= Yii::t('app', 'You') */?></div>
        </div>
    </div>-->

    <?= $this->render('__pizza_panel_elements_list', ['componentSets' => $componentSets]) ?>

    <div class="standard-pizzas-panel wrp-pane">
        <div class="btn-head"><?= Yii::t('app', 'Pizzas') ?></div>
        <div class="panel-elements-list">
            <div class="header"><?= Yii::t('app', 'Choose your pizza') ?></div>
            <div class="header-tip"><?= Yii::t('app', 'Or create it yourself ⇒') ?></div>
            <hr>
            <?php
            //TODO: pz_comp
            /*            $this->registerJs(<<<JS
            $('.ttt').click(function(e){
                e.preventDefault();
                gl.functions.unwrapBottom(this);
                return false;
            });
            JS
            );*/
            foreach ($componentSets as $cs) {
                if ($cs->components) {
                    $compsData = [];
                    foreach ($cs->components as $comp) {
                        $compsData[] = $comp->createHtmlDataParams();
                    }
                    //TODO: pz_comp
                    echo Html::tag('div', $cs->name, [
                        //'class' => 'elem ttt',
                        'class' => 'ttt',
                        'data-id' => $cs->id,
                        'data-name' => $cs->name,
                        'data-components' => json_encode($compsData),
                        'onclick' => 'gl.functions.unwrapBottom(this);return false;',
                    ]);

                    //print_r($compsData);exit;
                    $pizza20Data = array_merge([
                        [
                            'data-id' => 5300,
                            'data-name' => 'Тесто ⌀20см',
                            'data-short_name' => 'Тесто ⌀20см',
                            'data-price' => 200.00,
                            'data-price_discount' => 0,
                            'data-image' => '/img/compo/base_1.jpeg',
                            'data-image-text' => '⌀20',
                            'data-video' => '/video/construct/default.gif',
                            'data-item_select_min' => 1,
                            'data-item_select_max' => '',
                            'data-unit_name' => '',
                            'data-unit_value' => '',
                            'data-unit_value_min' => '',
                            'data-unit_value_max' => '',
                            'data-unit_switch_group_id' => 0,   //2,
                            'data-unit_switch_group_name' => 'Size of pizza',
                        ]
                    ], $compsData);

                    $pizza25Data = array_merge([
                        [
                            'data-id' => 5400,
                            'data-name' => 'Тесто ⌀25см',
                            'data-short_name' => 'Тесто ⌀25см',
                            'data-price' => 200.00,
                            'data-price_discount' => 0,
                            'data-image' => '/img/compo/base_1.jpeg',
                            'data-image-text' => '⌀25',
                            'data-video' => '/video/construct/default.gif',
                            'data-item_select_min' => 1,
                            'data-item_select_max' => '',
                            'data-unit_name' => '',
                            'data-unit_value' => '',
                            'data-unit_value_min' => '',
                            'data-unit_value_max' => '',
                            'data-unit_switch_group_id' => 0,   //2,
                            'data-unit_switch_group_name' => 'Size of pizza',
                        ]
                    ], $compsData);

                    $pizza27Data = array_merge([
                        [
                            'data-id' => 5500,
                            'data-name' => 'Тесто ⌀27см',
                            'data-short_name' => 'Тесто ⌀27см',
                            'data-price' => 200.00,
                            'data-price_discount' => 0,
                            'data-image' => '/img/compo/base_1.jpeg',
                            'data-image-text' => '⌀27',
                            'data-video' => '/video/construct/default.gif',
                            'data-item_select_min' => 1,
                            'data-item_select_max' => '',
                            'data-unit_name' => '',
                            'data-unit_value' => '',
                            'data-unit_value_min' => '',
                            'data-unit_value_max' => '',
                            'data-unit_switch_group_id' => 0,   //2,
                            'data-unit_switch_group_name' => 'Size of pizza',
                        ]
                    ], $compsData);

                    $conHtml = Html::tag('div', '⌀20см', [
                            'class' => 'elem elem-pi',
                            //'style' => 'display:none',
                            'data-id' => $cs->id,
                            'data-name' => $cs->name,
                            'data-components' => json_encode($pizza20Data),
                        ])
                        . Html::tag('div', '⌀25см', [
                            'class' => 'elem elem-pi',
                            //'style' => 'display:none',
                            'data-id' => $cs->id,
                            'data-name' => $cs->name,
                            'data-components' => json_encode($pizza25Data),
                        ])
                        . Html::tag('div', '⌀27см', [
                            'class' => 'elem elem-pi',
                            //'style' => 'display:none',
                            'data-id' => $cs->id,
                            'data-name' => $cs->name,
                            'data-components' => json_encode($pizza27Data),
                        ]);

                    echo Html::tag('div', $conHtml, [
                        'class' => 'elem',
                        'style' => 'background-color: #686666;display:none',
                        //'data-id' => $cs->id,
                        //'data-name' => $cs->name,
                        //'data-components' => json_encode($compsData),
                        'onclick' => 'return false;',
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
                <!--<iframe class="popup-iframe" id="frame-order-form"
                        src="<?php /*= Url::to(['/vendor/order-panel', 'uid' => $uid]) */ ?>"></iframe>-->
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
                            //'action' => Url::to(['/vendor/order-create']),
                        ]
                    ); ?>

                    <input type="hidden" name="user_uid" value="<?= Html::encode($uid) ?>">
                    <input type="hidden" id="order-id">
                    <input type="hidden" id="order-info">

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

<div id="userContractModal" class="contract-modal modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app_c', 'Contract') ?></h4>
            </div>
            <div class="modal-body">
                <?= \common\models\db\Texts::findOne('registering-customer-agreement')->content ?>
            </div>
        </div>

    </div>
</div>
