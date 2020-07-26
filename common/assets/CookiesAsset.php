<?php

namespace common\assets;

use yii\web\AssetBundle;

class CookiesAsset extends AssetBundle
{
    public $sourcePath = '@bower/js-cookie';
    public $js = [
        'src/js.cookie.js',
    ];
    /*public $depends = [
        'yii\web\JqueryAsset',
    ];*/
}
