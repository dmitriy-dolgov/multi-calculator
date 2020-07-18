<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class VendorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/vendor.css?v=1.5',
    ];
    public $js = [
        'js/common.js',
        //'js/jquery.ui.touch-punch.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'frontend\assets\JtpAsset',
        'frontend\assets\JplayerAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
