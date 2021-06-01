<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\Geopoint */

$this->title = Yii::t('app', ' ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Geopoints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geopoint-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
