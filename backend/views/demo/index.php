<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест');

$this->registerJs(<<<JS
gl.functions.panelsSwitch = function (direction) {
    console.log('direction:', direction);
    $('#vendor').toggleClass('switched');
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
