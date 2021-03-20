<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест');

?>
<iframe class="frame left" id="vendor" src="<?= Url::to(['/demo/vendor', '_' => time()]) ?>" marginwidth="0" height="300"
        marginheight="0" align="top" scrolling="No" frameborder="0" hspace="0" vspace="0"></iframe>
<iframe class="frame right" id="customer" src="<?= Url::to(['/demo/customer', '_' => time()]) ?>" marginwidth="0" height="300"
        marginheight="0" align="top" scrolling="No" frameborder="0" hspace="0" vspace="0"></iframe>
