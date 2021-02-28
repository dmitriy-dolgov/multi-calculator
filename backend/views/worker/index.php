<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $worker \common\models\db\CoWorker */
/* @var $coWorkerFunctions \common\models\db\CoWorkerFunction[] */
/* @var $orders array */

$this->title = Yii::t('app', 'Co-worker main page');

$this->registerJs(<<<JS
    if (!window.gl) {
        window.gl = {};
    }
    if (!gl.functions) {
        gl.functions = {};
    }
    
    if (!gl.functions.orders) {
        gl.functions.orders = {};
    }
    if (!gl.functions.startTimerOut) {
        gl.functions.startTimerOut = function (orderUid, createdAt) {
            document.addEventListener("DOMContentLoaded", function() {
                var orderCreationDateTimeElem = $('.time-passed[data-uid=' + orderUid + ']');
                var date = new Date(createdAt);
                //console.log('date: ' + date);
                setInterval(function() {
                    var currentDateTime = new Date();
                    orderCreationDateTimeElem.text(parseInt((currentDateTime - date) / 1000) + ' сек.');
                }, 3000);
            });
        };
    }
JS
    , \yii\web\View::POS_HEAD
);

$this->registerJs(<<<JS
$('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
});

$('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
});
JS
);

$this->registerCss(<<<CSS
.order-properties {
    width: 100%;
}
.btn-accept-order-wrap {
    width: 100%;
}
.btn-accept-order-wrap button {
    width: 100%;
    margin-top: 10px;
}
.order-properties .value {
    text-align: right;
    font-weight: bold;
}


.panel-heading {
  padding: 0;
	border:0;
}
.panel-title>a, .panel-title>a:active{
	display:block;
	padding:15px;
  color:#555;
  font-size:16px;
  font-weight:bold;
	text-transform:uppercase;
	letter-spacing:1px;
  word-spacing:3px;
	text-decoration:none;
}
.panel-heading  a:before {
   font-family: 'Glyphicons Halflings';
   content: "\e114";
   float: right;
   transition: all 0.5s;
}
.panel-heading.active a:before {
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);
}
CSS
);

?>
<header class="header">
    <div class="caption"><?= Html::encode(Yii::t('app', 'Individual Co-Worker`s Site')) ?></div>
    <div class="info">
        <div><?= Html::encode($worker->name) ?></div>
        <ul class="function-list">
            <?php if ($coWorkerFunctions): ?>
                <?php foreach ($coWorkerFunctions as $id => $name): ?>
                    <li class="cw-function <?= $id ?>"><?= Html::encode(Yii::t('db', $name)) ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <?= Yii::t('app', 'No tasks. Please refer to your manager.') ?>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>

<main class="body">

    <?= \common\widgets\Alert::widget() ?>

    <?php foreach ($orders as $workerType => $orderList): ?>
        <div class="function-orders-pane <?= $workerType ?>">
            <?php
            if ($workerType == 'accept_orders') {
                echo $this->render('_accept_orders', ['orderList' => $orderList, 'worker' => $worker]);
            } elseif ($workerType == 'maker') {
                echo $this->render('_maker', ['orderList' => $orderList, 'worker' => $worker]);
            } elseif ($workerType == 'courier') {
                echo $this->render('_courier', ['orderList' => $orderList, 'worker' => $worker]);
            }
            ?>
        </div>
    <?php endforeach; ?>
</main>
