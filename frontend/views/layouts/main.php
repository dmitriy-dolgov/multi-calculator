<?php

/* @var $this \yii\web\View */

/* @var $content string */

use common\widgets\Alert;
use yii\helpers\Html;

\frontend\assets\VendorAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="main-container">
    <?= Alert::widget() ?>
    <?= $content ?>
</div>

<h1 id="alert-text-1">
    <svg role="img"><title class="title">Random Words!</title>
        <text class="word" dominant-baseline="central" fill="#222" stroke="#222" stroke-linecap="round"
              stroke-width="1.5%" text-anchor="middle" x="50%" y="50%">gravity!
        </text>
        <text class="word" dominant-baseline="central" fill="white" text-anchor="middle" x="50%" y="50%">gravity!</text>
    </svg>
</h1>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
