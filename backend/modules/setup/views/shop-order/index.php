<?php

use common\models\db\ShopOrder;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\ShopOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Order List');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.shop-order-index td hr {
    margin: 7px 0;
    border-top: 1px solid #848484;
}
.amount-caption {
    color: black;
}
/*
.iframe-detail {
    width: 100%;
    height: 500px;
}*/

CSS
);

?>
<div class="shop-order-index">

    <!--<h1><? /*= Html::encode($this->title) */ ?></h1>-->

    <?php

    $columns =
        [
            ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
            //'id',
            //'user_id',
            /*[
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '40px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                // uncomment below and comment detail if you need to render via ajax
                // 'detailUrl' => Url::to(['/site/book-details']),
                'detail' => function ($model, $key, $index, $column) {
                    //return Yii::$app->controller->renderPartial('_expand-row-details', ['model' => $model]);
//                    return '<iframe class="iframe-detail" src="' . Html::encode(Url::to([
//                            '/setup/shop-order/view',
//                            'id' => $model->id
//                        ])) . '"></iframe>';
                    //return Yii::$app->controller->renderPartial('_view', ['model' => $model]);
                    return $this->renderPartial('_view', ['model' => $model]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true,
            ],*/
            'deliver_customer_name',
            'deliver_address:ntext',
            //'deliver_phone',
            //'deliver_email:email',
            'deliver_comment:ntext',
            //'order_uid',
            [
                'label' => Yii::t('app', 'Order UID'),
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        $model->order_uid,
                        Url::to(['/vendor/order-info', 'uid' => $model->order_uid]),
                        ['target' => '_blank']
                    );
                },
            ],
            'created_at',
            //'deliver_required_time_begin',
            //'deliver_required_time_end',
            /*[
                'label' => Yii::t('app', 'Components'),
                'format' => 'html',
                'value' => function (ShopOrder $model) {
                    $compList = [];
                    foreach ($model->shopOrderComponents as $component) {
                        $compList[] = Html::a($component->name,
                            ['/setup/component/view', 'id' => $component->component->id],
                            ['target' => '_blank']);
                    }
                    return implode('<hr>', $compList);
                },
            ],*/
            [
                'label' => Yii::t('app', 'Status'),
                'format' => 'html',
                'value' => function (ShopOrder $model) {
                    $usersText = '<div class="amount-caption">'
                        . Yii::t('app', 'Total pizzerias: {amount}', ['amount' => $model->getAmountOfUsers()])
                        . '</div>';

                    $shoStatuses = Yii::$app->user->identity->getShopOrderStatuses()->andWhere(['shop_order_id' => $model->getPrimaryKey()])->all();
                    //Yii::$app->user->identity->getShopOrderStatuses()

                    $statusList = [];
                    foreach ($shoStatuses as $status) {
                        $statusList[] = $status->getStatusName();
                    }
                    return $usersText . '<hr>' . implode('<hr>', $statusList);
                },
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                /*'urlCreator' => function ($action, $model, $key, $index) {
//                    if ($action == 'view') {
//                        return Url::to(['/setup/shop-order/view', 'id' => $model->id]);
//                    }
                    return '#';
                },*/
                'template' => '{view}',
                //'viewOptions' => ['title' => $viewMsg, 'data-toggle' => 'tooltip'],
                'order' => DynaGrid::ORDER_FIX_RIGHT
            ],

            /*[
                'attribute' => 'name',
                'pageSummary' => 'Page Total',
                'vAlign' => 'middle',
                'order' => DynaGrid::ORDER_FIX_LEFT
            ],
            [
                'attribute' => 'color',
                'value' => function ($model, $key, $index, $widget) {
                    return "<span class='badge' style='background-color: {$model->color}'> </span>  <code>" .
                        $model->color . '</code>';
                },
                'filterType' => GridView::FILTER_COLOR,
                'filterWidgetOptions' => [
                    'showDefaultPalette' => false,
                    'pluginOptions' => [
                        'showPalette' => true,
                        'showPaletteOnly' => true,
                        'showSelectionPalette' => true,
                        'showAlpha' => false,
                        'allowEmpty' => false,
                        'preferredFormat' => 'name',
                        'palette' => [
                            [
                                "white",
                                "black",
                                "grey",
                                "silver",
                                "gold",
                                "brown",
                            ],
                            [
                                "red",
                                "orange",
                                "yellow",
                                "indigo",
                                "maroon",
                                "pink"
                            ],
                            [
                                "blue",
                                "green",
                                "violet",
                                "cyan",
                                "magenta",
                                "purple",
                            ],
                        ]
                    ],
                ],
                'vAlign' => 'middle',
                'format' => 'raw',
                'width' => '150px',
                'noWrap' => true
            ],
            [
                'attribute' => 'publish_date',
                'filterType' => GridView::FILTER_DATE,
                'format' => 'raw',
                'width' => '170px',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                ],
                'visible' => false,
            ],
            [
                'attribute' => 'author_id',
                'vAlign' => 'middle',
                'width' => '250px',
                'value' => function ($model, $key, $index, $widget) {
                    return Html::a($model->author->name, '#', [
                        'title' => 'View author detail',
                        'onclick' => 'alert("This will open the author page.\n\nDisabled for this demo!")'
                    ]);
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Author::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Any author'],
                'format' => 'raw'
            ],
            [
                'attribute' => 'buy_amount',
                'hAlign' => 'right',
                'vAlign' => 'middle',
                'width' => '100px',
                'format' => ['decimal', 2],
                'pageSummary' => true
            ],
            [
                'attribute' => 'sell_amount',
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'width' => '100px',
                'format' => ['decimal', 2],
                'pageSummary' => true
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'status',
                'vAlign' => 'middle',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'urlCreator' => function ($action, $model, $key, $index) {
                    return '#';
                },
                'viewOptions' => ['title' => $viewMsg, 'data-toggle' => 'tooltip'],
                'updateOptions' => ['title' => $updateMsg, 'data-toggle' => 'tooltip'],
                'deleteOptions' => ['title' => $deleteMsg, 'data-toggle' => 'tooltip'],
                'order' => DynaGrid::ORDER_FIX_RIGHT
            ],
            ['class' => 'kartik\grid\CheckboxColumn', 'order' => DynaGrid::ORDER_FIX_RIGHT],*/
        ];
    $dynagrid = DynaGrid::begin([
        'columns' => $columns,
        'theme' => 'panel-info',
        'showPersonalize' => true,
        'storage' => 'session',
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            //'showPageSummary' => true,
            //'floatHeader' => true,
            'pjax' => true,
            //'responsiveWrap' => false,
            'panel' => [
                'heading' => '<h3 class="panel-title"><i class="fa fa-list"></i> ' . Html::encode($this->title) . '</h3>',
                //'before' => '<div style="padding-top: 7px;"><em>* The table header sticks to the top in this demo as you scroll</em></div>',
                'after' => false,
            ],
            'toolbar' => [
                [
                    'content' =>
                    /*Html::button('<i class="fa fa-plus"></i>', [
                        'type' => 'button',
                        'title' => 'Add Book',
                        'class' => 'btn btn-success',
                        'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");'
                    ]) . ' ' .*/
                        Html::a('<i class="fa fa-repeat"></i>', ['dynagrid-demo'],
                            [
                                'data-pjax' => 0,
                                'class' => 'btn btn-outline-secondary',
                                'title' => Yii::t('app', 'Reset Grid')
                            ])
                ],
                ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
                '{export}',
            ]
        ],
        'options' => ['id' => 'dynagrid-1'] // a unique identifier is important
    ]);
    if (substr($dynagrid->theme, 0, 6) == 'simple') {
        $dynagrid->gridOptions['panel'] = false;
    }
    DynaGrid::end();

    ?>

</div>
