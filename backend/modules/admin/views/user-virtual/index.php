<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\db\UserVirtual;

/* @var $this yii\web\View */
/* @var $searchModel common\models\db\UserVirtualSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Виртуальный продавец');
$this->params['breadcrumbs'][] = $this->title;

//TODO: prompt - сделать i18n
$this->registerJs(<<<JS
$('.create-automatic').click(function(e) {
    e.preventDefault();
    
    var howMany = 3;
    do {
        var amountRaw = prompt('Сколько продавцов создать (от 1 до 50)?', '3');
        if (isNaN(amountRaw)) {
            return;
        }
        
        howMany = parseInt(amountRaw, 10);
        
    } while (howMany > 50 || howMany < 1);
    
    $.post('/admin/user-virtual/create-users?users_amount=' + howMany, function(data) {
    if (data && data.status == 'success') {
        location.reload();
    } else {
        alert('Произошла ошибка!');
    }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown);
    });
    
    return false;
});
JS
);

?>
<div class="user-virtual-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <h5><?= Html::encode(Yii::t('app', 'Создать виртуального пользователя:')) ?></h5>
    <?= Html::a(Yii::t('app', 'Вручную'), ['create'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Автоматически'), null, ['class' => 'create-automatic btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'user_id',
            [
                'label' => Yii::t('app', 'Данные владельца'),
                'format' => 'raw',
                'value' => function (UserVirtual $model) {
                    if ($model->user) {
                        return "{$model->user->username} ({$model->user->email})";
                    }

                    return Yii::$app->formatter->nullDisplay;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
