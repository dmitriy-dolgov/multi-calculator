<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\db\UserVirtual;

/* @var $this yii\web\View */
/* @var $model UserVirtual */

$this->title = $model->user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Виртуальные пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="user-virtual-view">

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
                'label' => Yii::t('app', 'Данные владельца'),
                'value' => function (UserVirtual $userVirtualModel) {
                    $user = $userVirtualModel->user;
                    return $user->username;
                }
            ],
        ],
    ]) ?>

</div>
