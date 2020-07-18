<?php

/* @var $this yii\web\View */
/* @var $model app\modules\vendor\models\OrderForm */

/* @var $uid integer */
/* @var $activeUsers app\models\db\User[] */
/* @var $components app\models\db\Component[] */
/* @var $componentSets app\models\db\ComponentSet[] */

?>
<main id="content">
    <?= $this->render('_content', [
        'uid' => $uid,
        'activeUsers' => $activeUsers,
        'components' => $components,
        'componentSets' => $componentSets,
    ]) ?>
</main>
