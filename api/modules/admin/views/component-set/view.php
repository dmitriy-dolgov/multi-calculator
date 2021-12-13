<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\ComponentSet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Component Sets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$componentSetHtml = Yii::$app->formatter->nullDisplay;
if (!empty($model->components)) {
    $componentSetHtml = [];
    foreach ($model->components as $component) {
        $componentSetHtml[] = Html::a(Html::img($component->getImageUrl(), [
                'style' => 'width:40px;margin-right:10px;margin-bottom:5px;',
            ]) . $component->name,
            ['component/view', 'id' => $component->id],
            ['target' => '_blank']);
    }
    $componentSetHtml = implode('<br>', $componentSetHtml);
}

?>
<div class="component-set-view">

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
            'name',

            [
                'label' => Yii::t('app', 'Component list'),
                'format' => 'raw',
                'value' => $componentSetHtml,
            ],
        ],
    ]) ?>

</div>
