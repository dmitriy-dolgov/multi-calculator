<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\UserVirtual */

$this->title = Yii::t('app', 'Create User Virtual');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Virtuals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-virtual-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
