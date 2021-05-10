<?php

use common\helpers\Web;
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - игра');
$this->registerCss(<<<CSS
#map-placeholder {
    width: 100%;
    height: 100vh;
    background-color: wheat;
}
CSS
);

$this->registerJs(<<<JS
    var mp = L.map('map-placeholder').setView([55.751244, 37.618423], 13);
    console.log("mymap: ", mp);
    
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap<\/a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA<\/a>',
    	maxZoom: 18
	}).addTo(mp);
JS
);

?>
<div id="map-placeholder"></div>
