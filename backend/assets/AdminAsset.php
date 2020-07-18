<?php

namespace backend\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/backend.css',
    ];
    public $js = [
        'js/common.js',
    ];
    public $depends = [
        'yiister\gentelella\assets\Asset',
        'yii\web\JqueryAsset',
    ];
}
