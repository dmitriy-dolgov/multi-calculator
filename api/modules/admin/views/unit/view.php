<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\Unit */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="unit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => Yii::t('app', 'Name'),
                'value' => $model->name . ' || ' . Yii::t('db', $model->name),
            ],
            [
                'label' => Yii::t('app', 'Short Name'),
                'value' => $model->short_name . ' || ' . Yii::t('db', $model->short_name),
            ],
            [
                'label' => Yii::t('app', 'Symbol'),
                'value' => $model->symbol . ' || ' . Yii::t('db', $model->symbol),
            ],
            [
                'label' => Yii::t('app', 'Symbol Pattern'),
                'value' => $model->symbol_pattern . ' || ' . Yii::t('db', $model->symbol_pattern),
            ],
        ],
    ]) ?>

</div>
