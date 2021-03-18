<?php

/* @var $this yii\web\View */

$this->registerCss(<<<CSS
.top-head {
    height: 150px;
}
CSS
);

?>
<div class="top-head">Пиццерия</div>
<iframe id="pizzeria-body" src="/worker?workerUid=orders"></iframe>
