<?php

use common\helpers\HtmlHelper;
use yii\helpers\Html;
use \yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - тест - страница продавца');

?>
<div id="handle-head">Продавец</div>
<?= Html::tag('iframe', '', array_merge(HtmlHelper::iframeParamsCleaned(),
        [
            'src' => Url::to(['/worker', 'workerUid' => 'orders']),
            'class' => 'body',
        ])
); ?>
