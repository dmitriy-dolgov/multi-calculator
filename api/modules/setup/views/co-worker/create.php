<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorker */
/* @var $coWorkerFunctions common\models\db\CoWorkerFunction[] */

$this->title = Yii::t('app', 'Create Co-worker');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Co-workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-worker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'coWorkerFunctions' => $coWorkerFunctions,
    ]) ?>

</div>
