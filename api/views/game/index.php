<?php

use common\helpers\Web;
use yii\helpers\Url;
use yii\helpers\Html;
use common\helpers\HtmlHelper;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Пицца Майя - игра');

$this->registerJs(<<<JS
    var placesMapId = 'places-map';
    var mapMarkers = [];
    var initialMapParameters = {
        latitude: 55.751244,
        longitude: 37.618423,
        zoom: 11
    };
    
    mapMarkers.push({
        id: 'tstId',
        latitude: 55.651244,        //55.751244
        longitude: 37.818423,      // 37.618423
        //icon: icon,
        popupHtml: 'Курьер Дима'

    });
    
    var placesMap = function (id, initialMapParameters) {

        var pulsingIcon = L.icon.pulse({iconSize: [15, 15], color: 'green', fillColor: 'red'});
        this.customerMarker = this.addMarkerByCoords(55.651244, 37.818423);
    
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }).addTo(this.map);
    };
    
    placesMap.prototype.addMarkersToMap = function (markers) {
        this.markers = [];
        if (markers.length) {
            for (var mId in markers) {
                var icon = markers[mId].icon ? markers[mId].icon : this.icons.defaultPizzeria;
                this.markers.push({
                    id: markers[mId].id,
                    marker: this.addMarkerByCoords(markers[mId].latitude, markers[mId].longitude, icon, markers[mId].popupHtml),
                });
            }
        }
    
        var allMarkersGroup = new L.featureGroup(this.allMarkers);
        this.map.fitBounds(allMarkersGroup.getBounds());
    };
    
    //this.connectAllMerchantsWithCustomer();
JS
);

?>
<div class="map-placeholder">


</div>
