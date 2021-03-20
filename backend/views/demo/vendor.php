<?php

use \yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест - страница продавца');

?>
<div id="handle-head">Продавец</div>
<iframe id="body" src="<?= Url::to(['/worker', 'workerUid' => 'orders']) ?>"></iframe>
