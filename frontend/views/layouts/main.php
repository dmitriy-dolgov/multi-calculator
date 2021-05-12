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

    <?php // Основные стили ?>
    <style>
        .pm-hidden {
            display: none;
        }
        .vendor-panel .video {
            background-image: url(/video/construct/default-local.gif) !important;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="main-container">
    <?= Alert::widget() ?>
    <?= $content ?>
</div>

<div id="alert-text-1" class="pm-hidden">
    <svg role="img">
        <text class="word" dominant-baseline="central" fill="#222" stroke="#222" stroke-linecap="round"
              stroke-width="1.5%" text-anchor="middle" x="50%" y="50%"></text>
        <text class="word" dominant-baseline="central" fill="white" text-anchor="middle" x="50%" y="50%"></text>
    </svg>
</div>

<?php $this->endBody() ?>

<script>
    //-------------------------------- Product text stuff added

    gl.functions.showUpgoingText = function(word) {
        var $word = $("#alert-text-1 .word");
        //word = '86675';
        $word.text(word);
        $("#alert-text-1").removeClass('pm-hidden');
    };

    $("#alert-text-1").on(
        "webkitAnimationIteration oanimationiteration msAnimationIteration animationiteration ",
        function () {
            //return;
            $("#alert-text-1").addClass('pm-hidden');
            //$word.text('uoifjusod');
        }
    );

    //gl.functions.showUpgoingText(7654);
</script>

</body>
</html>
<?php $this->endPage() ?>
