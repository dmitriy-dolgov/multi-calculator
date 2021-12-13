<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    // см. https://github.com/kartik-v/yii2-tree-manager как настроить для своей таблицы
    //TODO: потом настроить для Category
    echo \kartik\tree\TreeView::widget([
        'query' => \common\models\Tree::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => 'Store'],
        'rootOptions' => ['label'=>'<span class="text-primary">Products</span>'],
        'topRootAsHeading' => true, // this will override the headingOptions
        'fontAwesome' => true,
        'isAdmin' => true,
        'iconEditSettings'=> [
            'show' => 'list',
            'listData' => [
                'folder' => 'Folder',
                'file' => 'File',
                'mobile' => 'Phone',
                'bell' => 'Bell',
            ]
        ],
        'softDelete' => true,
        'cacheSettings' => ['enableCache' => true]
    ]);
    ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'short_name',
            'description',
            //'parent_category_id',
            [
                'label' => Yii::t('app', 'Parent category'),
                'value' => 'parentCategory.name',
            ],
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
