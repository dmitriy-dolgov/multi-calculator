<?php

namespace backend\assets;

use yii\web\AssetBundle;

class SetupAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/backend.css',
        'css/setup.css',
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
