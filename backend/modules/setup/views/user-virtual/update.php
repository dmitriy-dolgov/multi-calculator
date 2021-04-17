<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\UserVirtual */
/* @var $allUsers common\models\db\User */

$this->title = Yii::t('app', 'Обновить виртуального пользователя: {name}', [
    'name' => $model->user->username,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Виртуальный пользователь'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-virtual-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'allUsers' => $allUsers,
    ]) ?>

</div>
