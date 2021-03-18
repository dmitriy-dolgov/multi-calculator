<?php

/* @var $this yii\web\View */

use common\helpers\Web;

$this->title = Yii::t('app', 'Пицца Майя - тест');

$this->registerCss(<<<CSS
#t-header {
    height: 80px;
}
.frame {
    height: calc(100vh - 80px);
}
#pizzeria {
    width: 29%;
    float: left;
}
#customer {
    width: 69%;
    float: right;
}
CSS
);

?>
<div id="t-header"></div>
<iframe class="frame" id="pizzeria" src="/worker?workerUid=orders"></iframe>
<iframe class="frame" id="customer" src="<?= Web::getUrlToCustomerSite() ?>"></iframe>
