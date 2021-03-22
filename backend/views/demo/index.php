<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест');

$this->registerJs(<<<JS

gl.functions.panelsSwitch = function (direction) {
    console.log('direction:', direction);
    $('#tp').toggleClass('switched');
    $('#customer').toggleClass('switched');
}
JS
);

echo Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Url::to(['/demo/vendor', '_' => time()]),
            'id' => 'vendor',
            'class' => 'frame left',
        ])
);

echo Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Url::to(['/demo/customer', '_' => time()]),
            'id' => 'customer',
            'class' => 'frame right',
        ])
);

$this->registerCss(<<<CSS
#handle-head-left {
    position: fixed;
    left:0;
    top: 0;
    width: 30px;
    height: 30px;
}
#handle-head-right {
    position: fixed;
    right:0;
    top: 0;
    width: 30px;
    height: 30px;
}
CSS
);

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
        elem.html('&gt;');
    } else {
        //window.parent.gl.functions.panelsSwitch(false);
        gl.functions.panelsSwitch(false);
        elem.html('&lt;');
    }
}
JS
);

?>
<div id="handle-head-left">
    Продавец
    <button class="switch collapsed">&gt;</button>
</div>

<div id="handle-head-right">
    Покупатель
    <button class="switch">&gt;</button>
</div>



