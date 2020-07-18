<?php

namespace backend\assets;

use yii\web\AssetBundle;

class JtpAsset extends AssetBundle
{
    public $sourcePath = '@bower/jqueryui-touch-punch';
    public $js = [
        'jquery.ui.touch-punch.min.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
