<?php

use common\helpers\Internationalization;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

/* @var $pizzeriaModel \common\models\db\User */

$profile = $pizzeriaModel->profile;

$this->registerCssFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.css', [
    'integrity' => 'sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==',
    'crossorigin' => '',
]);

$this->registerJsFile('https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', [
    'integrity' => 'sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==',
    'crossorigin' => '',
]);


$mpData = Yii::$app->mapHandler->getStartPointParamsForUser();
$mpMarksJs = json_encode(Yii::$app->mapHandler->getUserMarkers());


$this->registerJs(<<<JS
(function() {

    var defaultMarks = $mpMarksJs;

    var map = L.map('place-map').setView([{$mpData['latitude']}, {$mpData['longitude']}], {$mpData['zoom']});
    
    if (defaultMarks.length) {
        var latLng = L.latLng(defaultMarks[0].latitude, defaultMarks[0].longitude);
        addMarker({latlng:latLng});
    }

	L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap<\/a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA<\/a>',
    	maxZoom: 18
	}).addTo(map);
	//var newMarkerGroup = new L.LayerGroup();
	//map.on('click', addMarker);
	
	/*function bindNewPopupToMarker() {
	    if (!currentMarker) {
	        return;
	    }
	    
	    currentMarker.unbindPopup();
	    
	    var popupHtml = gl.createMapMarkerPopupHtml({
            name:$('#profile-name').val(),
            address:$('#profile-location').val(),
            url:$('#profile-website').val()
        });
        if (popupHtml) {
            currentMarker.bindPopup(popupHtml);
        }
	}*/

	function addMarker(e) {
        new L.marker(e.latlng, {draggable:false}).addTo(map);
        
        //bindNewPopupToMarker();
    }
   
})();
JS
);

?>
<main id="pizzeria-info">
    <div class="name"><?= $profile->name ?></div>
    <?php if ($profile->public_email): ?>
        <div class="public-email"><?= 'Email: ' . Html::a($profile->public_email,
                'mailto:' . Html::encode($profile->public_email)) ?></div>
    <?php endif; ?>
    <?php if ($profile->location): ?>
        <div class="location"><?= $profile->location ?></div>
    <?php endif; ?>

    <hr>

    <div id="place-map" style="height:30vmax"></div>

</main>
