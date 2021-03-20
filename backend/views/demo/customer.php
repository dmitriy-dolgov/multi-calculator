<?php

use yii\helpers\Url;
use common\helpers\Web;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест - страница покупателя');

?>
<div id="handle-head">Покупатель</div>
<iframe class="body" id="customer-body" src="<?= Web::getUrlToCustomerSite() ?>"></iframe>
