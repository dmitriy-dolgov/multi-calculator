<?php

namespace backend\assets;

use yii\web\AssetBundle;

class DemoAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/reset.css',
        '/css/demo/wrapper.css',
    ];
    public $js = [
        '/js/shared/common.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}

//$this->registerCssFile('/css/reset.css', ['appendTimestamp' => YII_DEBUG]);
//$this->registerCssFile('/css/demo/wrapper.css', ['appendTimestamp' => YII_DEBUG]);
