<?php

/* @var $this yii\web\View */
/* @var $model frontend\modules\vendor\models\OrderForm */

/* @var $uid integer */
/* @var $activeUsers common\models\db\User[] */
/* @var $components common\models\db\Component[] */
/* @var $componentSets common\models\db\ComponentSet[] */

?>
<main id="content">
    <?= $this->render('_content', [
        'uid' => $uid,
        'activeUsers' => $activeUsers,
        'components' => $components,
        'componentSets' => $componentSets,
    ]) ?>
</main>
