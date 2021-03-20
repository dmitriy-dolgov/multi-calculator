<?php

use common\helpers\HtmlHelper;
use yii\helpers\Html;
use \yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест - страница продавца');

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
        window.parent.gl.functions.panelsSwitch(true);
        elem.html('&gt;');
    } else {
        window.parent.gl.functions.panelsSwitch(false);
        elem.html('&lt;');
    }
}
JS
);

?>
<div id="handle-head">
    Продавец
    <button class="switch collapsed">&gt;</button>
</div>

<?= Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Url::to(['/worker', 'workerUid' => 'orders']),
            'class' => 'body',
            'scrolling' => 'Yes',
        ])
); ?>
