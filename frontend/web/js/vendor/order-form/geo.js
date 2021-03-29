gl.functions.correctGeolocation = function () {
    //gl.log('correctGeolocation before');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            //gl.log('correctGeolocation IN FUNCTION');
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            if (gl.data.worldMap) {
                gl.data.worldMap.flyTo([lat, lng]);
            }
        });
    } else {
        alert(gl.data['geolocation-is-not-accessible']);
    }

    //gl.log('correctGeolocation return');
};

gl.functions.getCurrentGeoLocation = function () {
    var coords = {lat: 55.107540130615, lng: 33.267589569092};

    //gl.log('getCurrentGeoLocation before');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            gl.log('getCurrentGeoLocation IN FUNCTION');
            coords.lat = position.coords.latitude;
            coords.lng = position.coords.longitude;
            if (gl.data.worldMap) {
                if (gl.data.worldMap.courierMarker) {
                    gl.log('gl.data.worldMap.map.removeLayer(gl.data.worldMap.courierMarker)');
                    gl.data.worldMap.map.removeLayer(gl.data.worldMap.courierMarker);
                }
                gl.data.worldMap.courierMarker = gl.data.worldMap.addMarkerByCoords(coords.lat, coords.lng, gl.data.worldMap.icons.courier);
                //gl.data.worldMap.flyTo([coords.lat, coords.lng]);
            }
        });
    }

    /*var ff = function() {
        if (gl.data.worldMap) {
            if (gl.data.worldMap.courierMarker) {
                gl.log('gl.data.worldMap.map.removeLayer(gl.data.worldMap.courierMarker)');
                gl.data.worldMap.map.removeLayer(gl.data.worldMap.courierMarker);
            }
            gl.data.worldMap.courierMarker = gl.data.worldMap.addMarkerByCoords(coords.lat, coords.lng, gl.data.worldMap.icons.courier);
            gl.data.worldMap.flyTo([coords.lat, coords.lng]);
        }
    };
    ff();*/


    //gl.log('getCurrentGeoLocation return');
    return coords;
};

gl.functions.correctGeolocation();

gl.functions.SelectProviders = {
    'select-all': function () {
        $('.select-providers inpudragt[type="checkbox"]').prop('checked', true);
    },
    'unselect-all': function () {
        $('.select-providers input[type="checkbox"]').prop('checked', false);
    },
    'nearest': function () {
        alert('В разработке');
        return false;
    }
};

gl.functions.placesMap = function (id, initialMapParameters) {
    this.map = L.map(id).setView([initialMapParameters.latitude, initialMapParameters.longitude], initialMapParameters.zoom);

    var pulsingIcon = L.icon.pulse({iconSize: [15, 15], color: 'green', fillColor: 'red'});
    //this.customerMarker = this.addMarkerByCoords(initialMapParameters.latitude, initialMapParameters.longitude, this.icons.customerIcon);
    this.customerMarker = this.addMarkerByCoords(initialMapParameters.latitude, initialMapParameters.longitude, pulsingIcon);

    /*gl.log('this.globalZIndex 0: ' + gl.functions.placesMap.globalZIndex);
    ++gl.functions.placesMap.globalZIndex;

    var customerMarker = this.customerMarker;
    this.customerMarker.on('click', function (e) {
        gl.log('this.globalZIndex 3: ' + gl.functions.placesMap.globalZIndex);
        customerMarker.setZIndexOffset(++gl.functions.placesMap.globalZIndex);
    });*/

    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(this.map);

    //this.showCourier();
};

gl.functions.placesMap.prototype.showCourier = function () {
    var latLng = gl.functions.getCurrentGeoLocation();
    this.courierMarker = this.addMarkerByCoords(latLng.lat, latLng.lng, this.icons.courier);

    gl.functions.placesMap.prototype.moveCourier(latLng, this.courierMarker);
};

gl.functions.placesMap.prototype.hideCourier = function () {
    //var latLng = gl.functions.getCurrentGeoLocation();
    //this.courierMarker = this.addMarkerByCoords(latLng.lat, latLng.lng, this.icons.courier);
};

gl.functions.placesMap.prototype.moveCourier = function (latLng, courierMarker) {
    //var latLng = gl.functions.getCurrentGeoLocation();
    //this.courierMarker
    //var fromCoords = ;
    //var customerLatLon = this.courierMarker.getLatLng();
    var customerLatLon = latLng;
    var mrkLanLng = window.ttt;
    /*for (var mId in this.markers) {
        mrkLanLng = this.markers[mId].marker.getLatLng();
        break;
    }

    debugger;*/
    console.log('customerLatLon: ', customerLatLon);
    console.log('mrkLanLng: ', mrkLanLng);

    var $pt1 = [customerLatLon.lat, customerLatLon.lng];
    var $pt2 = [mrkLanLng.lat, mrkLanLng.lng];
    var $m = ($pt1[1] - $pt2[1]) / ($pt1[0] - $pt2[0]);
    var $b = $pt1[1] - $m * $pt1[0];

    //for ($i = $pt1[0]; $i <= $pt2[0]; $i++)
    //$points[] = array($i, $m * $i + $b);

    var $i = $pt1[0];

    console.log('$i: ', $i);

    var courierMarkerObj = courierMarker;


    var step = ($pt2[0] - $i) / 50;

    //var counter = 0;
    var interval = setInterval(function () {
            console.log('$i: ', $i);
            if ($i <= $pt2[0]) {
                //this.courierMarker = this.addMarkerByCoords($i, $m * $i + $b, this.icons.courier);

                var newLatLng = new L.LatLng($i, $m * $i + $b);
                courierMarkerObj.setLatLng(newLatLng);

                console.log('$i: ', $i);

                $i += step; //0.01;
                return;
            }

            console.log('clearInterval');

            clearInterval(interval);
            interval = null;
        },
        100);

};

//gl.functions.placesMap.globalZIndex = 0;

gl.functions.placesMap.prototype.moveCustomerMarker = function (newLan, newLon) {
    var newLatLng = new L.LatLng(newLan, newLon);
    this.customerMarker.setLatLng(newLatLng);
};

gl.functions.placesMap.prototype.flyTo = function (lanLon) {
    this.map.flyTo(lanLon);
};

gl.functions.placesMap.prototype.allMarkers = [];
gl.functions.placesMap.prototype.allMovingMarkers = [];
gl.functions.placesMap.prototype.allPolylines = [];

gl.functions.placesMap.prototype.addMarkerByCoords = function (lat, lon, icon, popupHtml) {
    var newMarker;

    var latLng = L.latLng(lat, lon);
    if (icon) {
        newMarker = new L.marker(latLng, {icon: icon}).addTo(this.map);
    } else {
        newMarker = new L.marker(latLng).addTo(this.map);
    }

    if (popupHtml) {
        newMarker.bindPopup(popupHtml);
    }

    this.allMarkers.push(newMarker);

    /*newMarker.on('click', function (e) {
        gl.log('gl.functions.placesMap.globalZIndex 5: ' + gl.functions.placesMap.globalZIndex);
        newMarker.setZIndexOffset(++gl.functions.placesMap.globalZIndex);
    });*/

    return newMarker;
};

var glIconUrl = [
    '/img/map/courier-moto.png',
    '/img/courier/1.jpg',
    '/img/courier/2.png',
    '/img/courier/3.jpg',
    '/img/courier/4.gif',
];

glIconUrl = glIconUrl[0 + Math.floor((5 - 0) * Math.random())];
//glIconUrl = glIconUrl[0];

gl.functions.placesMap.prototype.icons = {
    defaultPizzeria: L.icon({
        iconUrl: '/img/map/default-pizzeria.png',
        //shadowUrl: 'leaf-shadow.png',

        iconSize: [50, 30] // size of the icon
        //shadowSize: [50, 64], // size of the shadow
        //iconAnchor: [22, 59], // point of the icon which will correspond to marker's location
        //shadowAnchor: [4, 62],  // the same for the shadow
        //popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
    }),
    customerIcon: L.icon({
        iconUrl: '/img/map/customer.gif',
        iconSize: [35, 35]
    }),
    carIcon: L.icon({
        iconUrl: '/img/map/sedan-car-model.svg',
        iconSize: [25, 25]
    }),
    movingTarget: L.icon({
        iconUrl: '/img/map/target.gif',
        iconSize: [11, 11]
    }),
    courier: L.icon({
        iconUrl: glIconUrl, //'/img/map/courier-moto.png',
        iconSize: [32, 32]
    }),
};

gl.functions.placesMap.prototype.addMarkersToMap = function (markers) {
    this.markers = [];
    if (markers.length) {
        for (var mId in markers) {
            var icon = markers[mId].icon ? markers[mId].icon : this.icons.defaultPizzeria;
            this.markers.push({
                id: markers[mId].id,
                marker: this.addMarkerByCoords(markers[mId].latitude, markers[mId].longitude, icon, markers[mId].popupHtml),
            });
            // ++gl.functions.placesMap.globalZIndex;
            // gl.log('this.globalZIndex 2: ' + gl.functions.placesMap.globalZIndex);
        }
    }

    // gl.log('this.globalZIndex 1: ' + gl.functions.placesMap.globalZIndex);
    // this.customerMarker.setZIndexOffset(gl.functions.placesMap.globalZIndex);

    var allMarkersGroup = new L.featureGroup(this.allMarkers);
    this.map.fitBounds(allMarkersGroup.getBounds());

    this.connectMarkersWithCustomer();
};

gl.functions.placesMap.prototype.connectMarkersWithCustomer = function () {
    //return;
    //this.customerMarksConnectors = [];

    /*for (var i in this.map._layers) {
        gl.log('LAYER ' + i);
        if (this.map._layers[i]._path != undefined) {
            gl.log('L path: ' + this.map._layers[i]._path);
            try {
                this.map.removeLayer(this.map._layers[i]);
            } catch (e) {
                console.log("problem with " + e + this.map._layers[i]);
            }
        } else {
            gl.log('L UNDEFINED');
        }
    }*/

    var geoJsonFeatureCollection = {
        type: 'FeatureCollection',
        features: []
    };

    var customerLatLon = this.customerMarker.getLatLng();
    for (var mId in this.markers) {
        var mrkLanLng = this.markers[mId].marker.getLatLng();
        /*var polylinePoints = [
            [customerLatLon.lat, customerLatLon.lng],
            [mrkLanLng.lat, mrkLanLng.lng]
        ];
        var newPolyline = L.polyline(polylinePoints, {weight: 1, opacity: .6, color: 'gray'});
        gl.functions.placesMap.prototype.allPolylines.push(newPolyline);
        newPolyline.addTo(this.map);*/

        geoJsonFeatureCollection.features.push({
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [customerLatLon.lng, customerLatLon.lat]
                },
                "properties": {
                    "origin_id": 0,
                    "origin_lon": customerLatLon.lng,
                    "origin_lat": customerLatLon.lat,
                    "destination_id": this.markers[mId].id,
                    "destination_lon": mrkLanLng.lng,
                    "destination_lat": mrkLanLng.lat
                }
            }
        );

        /*var myMovingMarker = L.Marker.movingMarker(polylinePoints, [1000], {
            autostart: true,
            loop: true,
            icon: this.icons.movingTarget
            //icon: this.icons.carIcon
        });
        gl.functions.placesMap.prototype.allMovingMarkers.push(myMovingMarker);
        myMovingMarker.addTo(this.map);
        //myMovingMarker.start();*/
    }

    this.flowmapLayer = L.canvasFlowmapLayer(geoJsonFeatureCollection, {
        originAndDestinationFieldIds: {
            originUniqueIdField: 'origin_id',
            originGeometry: {
                x: 'origin_lon',
                y: 'origin_lat'
            },
            destinationUniqueIdField: 'destination_id',
            destinationGeometry: {
                x: 'destination_lon',
                y: 'destination_lat'
            }
        },

        // some custom options
        pathDisplayMode: 'selection',
        animationStarted: true,
        animationEasingFamily: 'Cubic',
        animationEasingType: 'In',
        animationDuration: 2000,

        canvasBezierStyle: {
            type: 'simple',
            symbol: {
                // use canvas styling options (compare to CircleMarker styling below)
                strokeStyle: 'rgba(100, 100, 100, 0.8)',
                lineWidth: 0.75,
                lineCap: 'round',
                shadowColor: 'rgb(10, 10, 10)',
                shadowBlur: 1.5
            }
        },

        animatedCanvasBezierStyle: {
            type: 'simple',
            symbol: {
                // use canvas styling options (compare to CircleMarker styling below)
                strokeStyle: 'rgb(255, 88, 88)',
                //lineWidth: 1.25,
                lineWidth: 3,
                //lineDashOffsetSize: 4, // custom property used with animation sprite sizes
                lineDashOffsetSize: 7,
                lineCap: 'round',
                shadowColor: 'rgb(255, 88, 88)',
                shadowBlur: 2
            }
        }
    }).addTo(this.map);

    this.flowmapLayer.selectFeaturesForPathDisplayById('origin_id', 0, true, 'SELECTION_NEW');
};

/**
 * Удалить все соединения и установить соединение от пиццерии до пользователя.
 *
 * @param $merchantId ID пиццерии
 */
gl.functions.placesMap.prototype.connectAPizzeriaWithCustomer = function (merchantId,) {

    gl.log('connectAPizzeriaWithCustomer(), merchantId: ' + merchantId);

    if (this.flowmapLayer) {
        this.map.removeLayer(this.flowmapLayer);
    }

    var geoJsonFeatureCollection = {
        type: 'FeatureCollection',
        features: []
    };

    var customerLatLon = this.customerMarker.getLatLng();
    for (var mId in this.markers) {
        gl.log('this.markers[mId].id: ' + this.markers[mId].id);
        if (this.markers[mId].id != merchantId) {
            continue;
        }

        var mrkLanLng = this.markers[mId].marker.getLatLng();

        window.ttt = mrkLanLng;

        geoJsonFeatureCollection.features.push({
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [mrkLanLng.lng, mrkLanLng.lat]
                    //"coordinates": [customerLatLon.lng, customerLatLon.lat]
                },
                "properties": {
                    "origin_id": this.markers[mId].id,
                    "origin_lon": mrkLanLng.lng,
                    "origin_lat": mrkLanLng.lat,
                    "destination_id": 0,
                    "destination_lon": customerLatLon.lng,
                    "destination_lat": customerLatLon.lat
                }
            }
        );

        this.flowmapLayer = L.canvasFlowmapLayer(geoJsonFeatureCollection, {
            originAndDestinationFieldIds: {
                originUniqueIdField: 'origin_id',
                originGeometry: {
                    x: 'origin_lon',
                    y: 'origin_lat'
                },
                destinationUniqueIdField: 'destination_id',
                destinationGeometry: {
                    x: 'destination_lon',
                    y: 'destination_lat'
                }
            },

            // some custom options
            pathDisplayMode: 'selection',
            animationStarted: true,
            animationEasingFamily: 'Cubic',
            animationEasingType: 'In',
            animationDuration: 2000,

            canvasBezierStyle: {
                type: 'simple',
                symbol: {
                    // use canvas styling options (compare to CircleMarker styling below)
                    strokeStyle: 'rgba(0, 255, 51, 0.8)',
                    lineWidth: 0.75,
                    lineCap: 'round',
                    shadowColor: 'rgb(0, 255, 51)',
                    shadowBlur: 1.5
                }
            },

            animatedCanvasBezierStyle: {
                type: 'simple',
                symbol: {
                    // use canvas styling options (compare to CircleMarker styling below)
                    strokeStyle: 'rgb(0, 255, 88)',
                    lineWidth: 1.25,
                    lineDashOffsetSize: 4, // custom property used with animation sprite sizes
                    lineCap: 'round',
                    shadowColor: 'rgb(0, 255, 51)',
                    shadowBlur: 2
                }
            }
        }).addTo(this.map);

        this.flowmapLayer.selectFeaturesForPathDisplayById('origin_id', this.markers[mId].id, true, 'SELECTION_NEW');

        break;
    }
};
