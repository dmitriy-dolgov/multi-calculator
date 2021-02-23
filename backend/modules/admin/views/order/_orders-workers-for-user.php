<?php

/**
 * @var $this      yii\web\View
 */

/*$this->registerCss(<<<CSS
.worker-iframe {
    width: 100%;
    height: 500px;
}
CSS
);*/

?>
<iframe class="worker-iframe" src="/worker?workerUid=orders" style="width: 100%;height: 350px;"></iframe>
<iframe class="worker-iframe" src="/worker?workerUid=courier" style="width: 100%;height: 350px;"></iframe>
<!--<iframe class="worker-iframe" src="/worker?workerUid=cook" style="width: 100%;height: 350px;"></iframe>-->
