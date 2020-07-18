<?php

/* @var $this yii\web\View */

/* @var $orderHtml string */

use yii\helpers\Html;

?>
<div class="pg-link-to-embed">
    <?= Yii::t('app', 'Copy the html code and paste it into your page.') ?>
    <br>
    <br>
    <input style="width: 100%" type="text" readonly="readonly" value="<?= Html::encode($orderHtml) ?>">

    <hr>

    <?= $orderHtml ?>
</div>