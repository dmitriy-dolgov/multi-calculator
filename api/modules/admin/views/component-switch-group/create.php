<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\ComponentSwitchGroup */

$this->title = Yii::t('app', 'Create Component Switch Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Component Switch Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="component-switch-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
