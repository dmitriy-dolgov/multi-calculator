<?php

namespace backend\assets;

use yii\web\AssetBundle;

class SetupAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/backend.css?ver=1.0',
        'css/setup.css?ver=1.0',
    ];
    public $js = [
        'js/common.js',
        'js/setup.js',
    ];
    public $depends = [
        'yiister\gentelella\assets\Asset',
        'yii\web\JqueryAsset',
    ];
}
