<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

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
            'short_name',
            'description',
            //'parent_category_id',
            //'parentCategory.name',
            [
                'label' => Yii::t('app', 'Parent category'),
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->parentCategory) {
                        return $model->parentCategory->name;
                    } else {
                        return Yii::$app->formatter->nullDisplay;
                        //return Yii::t('app', 'No parent category');
                    }
                },
            ],
            'deleted_at',
        ],
    ]) ?>

</div>
