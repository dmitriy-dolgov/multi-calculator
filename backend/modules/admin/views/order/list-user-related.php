<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var $this         yii\web\View
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $searchModel  Da\User\Search\UserSearch
 * @var $module       Da\User\Module
 */

$this->title = Yii::t('usuario', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;

$module = Yii::$app->getModule('user');
?>

<?php //$this->beginContent('@Da/User/resources/views/shared/admin_layout.php') ?>

<?php Pjax::begin() ?>
<div class="table-responsive">
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index) {
                        return 'sdfkjsdfl';
                    },
                    'expandTitle' => Yii::t('app', 'Expand orders'),
                    'expandAllTitle' => Yii::t('app', 'Expand all orders'),
                    'collapseTitle' => Yii::t('app', 'Collapse orders'),
                    'collapseAllTitle' => Yii::t('app', 'Collapse all orders'),
                    // show row expanded for even numbered keys
                    //'detailUrl' => Url::to(['/site/book-details']),
                    /*'value' => function ($model, $key, $index) {
                        if ($key % 2 === 0) {
                            return GridView::ROW_EXPANDED;
                        }
                        return GridView::ROW_COLLAPSED;
                    },*/
                    'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'expandOneOnly' => true,
                ],
                'username',
                'email:email',
            ],
        ]
    ); ?>
</div>
<?php Pjax::end() ?>

<?php //$this->endContent() ?>
