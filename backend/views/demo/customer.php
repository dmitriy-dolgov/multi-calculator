<?php

use common\helpers\HtmlHelper;
use common\helpers\Web;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест - страница покупателя');

?>
<div id="handle-head">Покупатель</div>
<?= Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Web::getUrlToCustomerSite(),
            'class' => 'body',
        ])
); ?>
