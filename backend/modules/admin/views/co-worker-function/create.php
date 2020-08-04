<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\CoWorkerFunction */

$this->title = Yii::t('app', 'Create Co Worker Function');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Co Worker Functions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-worker-function-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
