<?php

namespace backend\assets;

use yii\web\AssetBundle;

class JplayerAsset extends AssetBundle
{
    public $sourcePath = '@bower/jplayer';
    public $js = [
        'dist/jplayer/jquery.jplayer.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
