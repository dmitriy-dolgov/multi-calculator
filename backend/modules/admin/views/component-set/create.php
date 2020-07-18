<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\db\ComponentSet */

$this->title = Yii::t('app', 'Create Component Set');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Component Sets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="component-set-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
