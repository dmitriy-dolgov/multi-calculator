<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\HistoryProfile */

$this->title = Yii::t('app', 'Создать профиль истории');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Профиль истории'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-profile-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
