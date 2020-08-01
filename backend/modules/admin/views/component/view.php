<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\db\Component */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Components'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerCss(<<<CSS
.comp-img-prev {
    max-height: 100px;
    max-width: 15vw;
    margin-right: 1vw;
    /*min-width: 100px;*/
}
CSS
);

?>
<div class="component-view">

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
                'label' => Yii::t('app', 'Component set'),
                'value' => isset($model->componentSets[0]) ? $model->componentSets[0]->name : '-',
            ],
            [
                'label' => Yii::t('app', 'Category'),
                'format' => 'raw',
                'value' => $model->category ? Html::encode($model->category->name) : Yii::$app->formatter->nullDisplay,
            ],
            'name',
            'short_name',
            [
                'label' => Yii::t('app', 'Is active'),
                'format' => 'raw',
                'value' => $model->disabled
                    ? ('<span class="text-danger">' . Yii::t('app', 'Disabled') . '</span>')
                    : Yii::t('app', 'Active'),
            ],
            //'parent_component_id',
            //'category_id',
            'description:ntext',
            'short_description:ntext',
            //'price:currency',
            //'price_discount:currency',
            [
                'label' => Yii::t('app', 'Цена'),
                'value' => $model->price ? ($model->price . ' ₽') : Yii::t('app', 'For free'),
            ],
            [
                'label' => Yii::t('app', 'Цена без скидки'),
                'value' => $model->price_discount ? ($model->price_discount . ' ₽') : Yii::t('app', 'For free'),
            ],
            'amount:integer',
            //'deleted_at',
        ],
    ]) ?>

    <hr>

    <b><?= Yii::t('app', 'Images:') ?></b><br>

    <?php
    if ($model->componentImages) {
        $imageDir = Yii::$app->params['component_images']['url_path'];
        foreach ($model->componentImages as $image) {
            $imagePath = $imageDir . $image->relative_path;
            $imgTag = Html::img($imagePath, ['class' => 'comp-img-prev']);
            echo Html::a($imgTag, \yii\helpers\Url::to($imagePath), ['target' => '_blank']);
        }
    } else {
        echo Yii::t('app', 'No images');
    }
    ?>
    <br>

    <hr>

    <b><?= Yii::t('app', 'Videos:') ?></b><br>

    <?php
    if ($model->componentVideos) {
        $videoDir = \common\models\UploadComponentVideoForm::videoPathUrl();
        foreach ($model->componentVideos as $video) {
            $videoPath = $videoDir . $video->relative_path;
            $imgTag = Html::img($videoPath, ['class' => 'comp-img-prev']);
            echo Html::a($imgTag, \yii\helpers\Url::to($videoPath), ['target' => '_blank']);
        }
    } else {
        echo Yii::t('app', 'No videos');
    }
    ?>

    <br>

</div>
