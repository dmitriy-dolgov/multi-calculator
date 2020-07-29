<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class VendorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/vendor.css?v=1.93',
    ];
    public $js = [
        'js/common.js?v=1.2',
        //'js/jquery.ui.touch-punch.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'frontend\assets\JtpAsset',
        'frontend\assets\JplayerAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        //'common\assets\CookiesAsset',
    ];
}
