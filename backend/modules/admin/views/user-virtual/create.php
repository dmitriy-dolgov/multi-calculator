<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\UserVirtual */
/* @var $allUsers common\models\db\User */

$this->title = Yii::t('app', 'Создать виртуального продавца');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Виртуальные продавцы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-virtual-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'allUsers' => $allUsers,
    ]) ?>

</div>
