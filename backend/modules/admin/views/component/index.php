<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\Component;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\ComponentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Components');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="component-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="text-warning">
        <?= Yii::t('app',
            'Шаблоны компонентов, доступны продавцу продукта для копирования в свой список компонентов.') ?>
    </div>

    <br>

    <p>
        <?= Html::a(Yii::t('app', 'Create component'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'label' => Yii::t('app', 'Component sets'),
                'format' => 'raw',
                'value' => function (Component $model) {
                    $result = Yii::$app->formatter->nullDisplay;
                    if (empty($model->componentSets)) {
                        $result = [];
                        foreach ($model->componentSets as $compSet) {
                            $result[] = Html::encode($compSet->name);
                        }
                        $result = implode('<br>', $result);
                    }
                    return $result;
                },
            ],
            [
                'label' => Yii::t('app', 'Category'),
                'format' => 'raw',
                'value' => function (Component $model) {
                    return $model->category ? $model->category->name : Yii::$app->formatter->nullDisplay;
                },
            ],
            'name',
            'short_name',
            //'parent_component_id',
            //'category_id',
            //'deleted_at',
            //'description:ntext',
            //'short_description:ntext',
            'price',
            //'price_discount',
            [
                'label' => Yii::t('app', 'Is active'),
                'format' => 'raw',
                'value' => function (Component $model) {
                    return $model->disabled
                        ? ('<span class="text-danger">' . Yii::t('app', 'Disabled') . '</span>')
                        : Yii::t('app', 'Active');
                },
            ],
            'amount',
            [
                'label' => Yii::t('app', 'Images'),
                'format' => 'raw',
                'value' => function (Component $model) {
                    $html = '';

                    foreach ($model->componentImages as $img) {
                        $imgUrl = Yii::$app->params['component_images']['url_path'] . $img->relative_path;
                        $imgHtml = Html::img($imgUrl, ['class' => 'list-image-prev']);
                        //TODO: глюк какой-то из-за события накладываемого Yii - разобраться
                        //$html .= Html::a($imgHtml, $imgUrl, ['target' => '_blank']);
                        $html .= $imgHtml;
                    }

                    if (!$html) {
                        $html = Yii::$app->formatter->nullDisplay;
                    }

                    return $html;
                },
            ],
            [
                'label' => Yii::t('app', 'Videos'),
                'format' => 'raw',
                'value' => function (Component $model) {
                    $html = '';

                    foreach ($model->componentVideos as $img) {
                        //TODO: брать заставки когда появятся
                        $imgUrl = Yii::$app->params['component_videos']['url_path'] . $img->relative_path;
                        $imgHtml = Html::img($imgUrl, ['class' => 'list-image-prev']);
                        //TODO: глюк какой-то из-за события накладываемого Yii - разобраться
                        //$html .= Html::a($imgHtml, $imgUrl, ['target' => '_blank']);
                        $html .= $imgHtml;
                    }

                    if (!$html) {
                        $html = Yii::$app->formatter->nullDisplay;
                    }

                    return $html;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
