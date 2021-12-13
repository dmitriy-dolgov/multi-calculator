<?php

use common\helpers\Web;use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест');

$this->registerJs(<<<JS

gl.functions.panelsSwitch = function (direction) {
    console.log('direction:', direction);
    $('#tp').toggleClass('switched');
    //$('#customer').toggleClass('switched');
    //$('#vendor').toggleClass('switched');
}
JS
);

echo Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Url::to(['/worker', 'workerUid' => 'orders']),
            //'class' => 'body',
            'class' => 'frame left',
            'scrolling' => 'Yes',
            'id' => 'vendor',
        ])
);

echo Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Web::getUrlToCustomerSite(),
            //'class' => 'body',
            'class' => 'frame right',
            'id' => 'customer',
        ])
);

/*echo Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Url::to(['/demo/vendor', '_' => time()]),
            'id' => 'vendor',
            'class' => 'frame left switched',
        ])
);

echo Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Url::to(['/demo/customer', '_' => time()]),
            'id' => 'customer',
            'class' => 'frame right',
        ])

*/
$this->registerJs(<<<JS
$('.switch').click(function() {
    var elem = $(this);
    elem.toggleClass('collapsed');
    doSwitch(elem)
});
function doSwitch(elem) {
    //console.log(window.parent);
    //console.log(window.parent.document);
    if (elem.hasClass('collapsed')) {
        //window.parent.gl.functions.panelsSwitch(true);
        gl.functions.panelsSwitch(true);
        //elem.html('&gt;');
    } else {
        //window.parent.gl.functions.panelsSwitch(false);
        gl.functions.panelsSwitch(false);
        //elem.html('&lt;');
    }
}
JS
);

?>
<div id="handle-head-left" class="switch"><!--
    Продавец
    <button class="switch collapsed">&gt;</button>-->
</div><div id="handle-head-right" class="switch"><!--
    Покупатель
    <button class="switch">&lt;</button>-->
</div>