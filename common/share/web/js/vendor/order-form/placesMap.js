/**
 * placesMap object
 */

function getRandom(min, max) {
    return Math.random() * (max - min) + min;
}

gl.functions.placesMap = function (id, initialMapParameters) {
    this.map = L.map(id).setView([initialMapParameters.latitude, initialMapParameters.longitude], initialMapParameters.zoom);

    var mapCopy = this.map;

    this.map.on('zoomend', function () {

        //var mapCopy = this.map;
        var currentZoom = mapCopy.getZoom();

        //$('.leaflet-marker-icon').each(function () {
        $('.leaflet-marker-icon.leaflet-zoom-animated.leaflet-interactive').each(function (e) {
            debugger;   // ACT
            debugger;

            var markerObj = $(this);

            if (markerObj[0] && markerObj[0].extInfo) {
                debugger;
                alert('O SIZE pe+++++++++++++++++++++++++++');
            }

            debugger;
            debugger;
            //if (!(markerObj.extInfo && markerObj.extInfo[0] && markerObj.extInfo[0].)) {
            if (!(markerObj[0] && markerObj[0].extInfo && markerObj[0].extInfo.doNotResize)) {
                debugger;
                debugger;
                console.log('(mapCopy.getZoom() * 3 + 3)', (mapCopy.getZoom() * 3 + 3));
                markerObj.css('width', (mapCopy.getZoom() * 3 + 3) + 'px');
                markerObj.css('height', 'auto');
                //alert('Check');
            } else {
                debugger;
                alert('NO SIZE');
            }

            return false;
        });
    });

    debugger;
    debugger;
    // это маркер покупателя, координаты Москвы для теста
    this.customerMarker = this.addMarkerByCoords(
        55.7522200,
        37.6155600,
        L.icon.pulse({iconSize: [15, 15], color: 'black', fillColor: 'red'}),
        false,
        {doNotResize: true}
    );

    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(this.map);

    //var mrkLanLng = L.latLng(initialMapParameters.latitude, initialMapParameters.longitude);

    if (this.markers) {
        //alert('if (this.markers) {');
        // debugger;
        // debugger;
        // //var clusterMarkers = L.markerClusterGroup();
        // for (var mId in this.markers) {
        //     var clustMarker = L.marker([  ////const clustMarker = L.marker([
        //         this.markers[mId].marker.getLat(),
        //         this.markers[mId].marker.getLng(),
        //     ]);
        //     clusterMarkers.addLayer(clustMarker);
        // }
        // this.map.addLayer(clusterMarkers);

        //TODO: !!! всегда выбирается первый магазин - сделать чтобы выбирался принявший заказ !!!
        for (var mId in this.markers) {
            mrkLanLng = this.markers[mId].marker.getLatLng();
            break;
        }
        if (this.markers && this.markers[0]) {
            //alert('this.markers');
            this.showCourierByLatLng(mrkLanLng);
        }
    } else {
        //TODO: надо ли обрабатывать?
        //alert('if (this.markers) { - ELSE');
        //gl.log(['HERE === this.markers:', this.markers]);
        //console.log('HERE === this.markers:', this.markers);
    }
};


gl.functions.placesMap.prototype.allMarkers = [];
gl.functions.placesMap.prototype.allMovingMarkers = [];
gl.functions.placesMap.prototype.allPolylines = [];
gl.functions.placesMap.prototype.merchantsLayers = [];
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
        iconSize: [35, 35],
        className: 'map-marker-icon'
    }),
    courierStand: L.icon({
        iconUrl: '/img/map/courier-moto.png',
        iconSize: [42, 42],
        className: 'map-marker-icon'
    }),
};


gl.functions.placesMap.prototype.hideCourier = function () {
    //var latLng = gl.functions.getCurrentGeoLocation();
    //this.courierMarker = this.addMarkerByCoords(latLng.lat, latLng.lng, this.icons.courier);
};

//TODO: не нужно?
gl.functions.placesMap.prototype.moveCustomerMarker = function (newLan, newLgn) {
    var newLatLng = new L.LatLng(newLan, newLgn);
    this.customerMarker.setLatLng(newLatLng);
};

//TODO: не нужно?
gl.functions.placesMap.prototype.flyTo = function (lanLon) {
    this.map.flyTo(lanLon);
};

gl.functions.placesMap.prototype.addMarkerByCoords = function (lat, lng, icon, popupHtml, extInfo) {

    gl.log('addMBCC !!!!!!!!!!!!!!!!!!!!');

    //debugger;
    //var newMarker;
    var latLng = L.latLng(lat, lng);

    if (!extInfo) {
        extInfo = {};
    }

    if (icon) {
        extInfo.icon = icon;
    } /*else {
        extInfo.addMarkerByCoords = true;
    }*/

    var newMarker = new L.marker(latLng, extInfo);

    if (popupHtml) {
        newMarker.bindPopup(popupHtml);
    } else {
        newMarker.bindPopup('no html popup !!!');
    }

    //var newMarker = new L.marker(latLng, extInfo);

    newMarker.addTo(this.map);

    //alert('p909');
    /*console.log('newMarker:');
    console.log(newMarker);

    debugger;
    debugger;
    newMarker.extInfo. = extInfo;*/


    /*newMarker.on('click', function (e) {
        gl.log('gl.functions.placesMap.globalZIndex 5: ' + gl.functions.placesMap.globalZIndex);
        newMarker.setZIndexOffset(++gl.functions.placesMap.globalZIndex);
    });*/

    //TODO: проверить закомментированое!!!
    this.allMarkers.push(newMarker);

    return newMarker;
};

// Главная функция наполнения this.markers
// По идее this.markers должна содержать все маркеры.
gl.functions.placesMap.prototype.addMarkersToMap = function (markerInfo) {

    this.markers = [];
    if (markerInfo.length) {

        var clusteredMarkers = L.markerClusterGroup();

        for (var mId in markerInfo) {

            //debugger;

            //var icon = markers[mId].icon ? markers[mId].icon : this.icons.defaultPizzeria;

            var newMarker = this.addMarkerByCoords(
                markerInfo[mId].latitude,
                markerInfo[mId].longitude,
                (markerInfo[mId].icon ? markerInfo[mId].icon : this.icons.defaultPizzeria),
                markerInfo[mId].popupHtml,
                {idKey: markerInfo[mId].id});

            this.markers.push({
                id: markerInfo[mId].id,
                marker: newMarker
            });

            clusteredMarkers.addLayer(newMarker);
            //this.allMarkers.push(newMarker);
        }

        // ???? //this.allMarkers.push(newMarker);

        this.map.addLayer(clusteredMarkers);
    }


    // gl.log('this.globalZIndex 1: ' + gl.functions.placesMap.globalZIndex);
    // this.customerMarker.setZIndexOffset(gl.functions.placesMap.globalZIndex);

    var allMarkersGroup = new L.featureGroup(this.allMarkers);
    //this.map.fitBounds(allMarkersGroup.getBounds());  // добавит ли this.connectAllMerchantsWithCustomer() к ширине ???

    this.connectAllMerchantsWithCustomer();

    //this.map.fitBounds(allMarkersGroup.getBounds());
};

/**
 * Удалить все соединения между покупателем и продавцами.
 */
gl.functions.placesMap.prototype.removeAllConnectionsBetweenCustomerAndMerchants = function () {
    // if (this.merchantsLayers) {
    //     this.map.removeLayer(this.merchantsLayers);
    //     //TODO: надо ли?
    //     this.merchantsLayers = null;
    // }

    for (var i in this.merchantsLayers) {
        this.map.removeLayer(this.merchantsLayers[i]);
    }
    this.merchantsLayers = [];
};

/**
 * Соединить покупателя с точкой продажи предполагаемой линией пути.
 * Анимация (если есть) идет от торговой точки до покупателя.
 * Если пользователь не указан - берется пользователь по умолчанию.
 *
 * @param merchantData данные точки продавца.
 * @param customerObj объект пользователя (opt.).
 */
gl.functions.placesMap.prototype.connectMerchantWithCustomerRealPath = function (merchantData, customerObj) {
    // if (!customerObj) {
    //     customerObj = this.customerMarker;
    // }
    // //TODO: customerLatLon - где используется ???
    // var customerLatLon = customerObj.getLatLng();

    var merchantLanLng = gl.getObject('map.coordinates').string2array(merchantData.company_lat_long);

    this.showCourierByLatLng(merchantLanLng);
};

/**
 * Соединить покупателя с точкой продажи.
 * Анимация идет от покупателя к торговой точке.
 * Если пользователь не указан - берется пользователь по умолчанию.
 *
 * @param merchantObj объект продавца.
 * @param customerObj объект пользователя (opt.).
 */
gl.functions.placesMap.prototype.connectMerchantWithCustomer = function (merchantObj, customerObj) {

    if (!customerObj) {
        customerObj = this.customerMarker;
    }

    var customerLatLon = customerObj.getLatLng();
    var merchantLatLng = merchantObj.getLatLng();
    var geoJsonFeatureCollection = {
        type: 'FeatureCollection',
        features: []
    };


    //debugger;
    //debugger;
    console.log('merchantObj', merchantObj);
    //merchantObj = ''
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
            //"destination_id": merchantObj.extInfo.idKey,
            "destination_id": merchantObj.options.idKey,
            "destination_lon": merchantLatLng.lng,
            "destination_lat": merchantLatLng.lat
        }
    });

    var newLayer = L.canvasFlowmapLayer(geoJsonFeatureCollection, {
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
                lineWidth: 3,
                lineDashOffsetSize: 7,  // custom property used with animation sprite sizes
                lineCap: 'round',
                shadowColor: 'rgb(255, 88, 88)',
                shadowBlur: 2
            }
        }
    }).addTo(this.map);

    //this.merchantsLayers.push(newLayer);
    newLayer.selectFeaturesForPathDisplayById('origin_id', 0, true, 'SELECTION_NEW');

    return newLayer;
};

/**
 * Соединить покупателя со всеми точками продажи.
 * Если данные покупателя не указаны - берутся данные покупателя по умолчанию.
 *
 * @param customerObj координаты пользователя (opt.).
 */
gl.functions.placesMap.prototype.connectAllMerchantsWithCustomer = function (customerObj) {

    if (!customerObj) {
        customerObj = this.customerMarker;
    }

    //alert('connectAllMerchantsWithCustomer for');
    //debugger;   // ACT
    //var clusterMarkers = L.markerClusterGroup();
    for (var mId in this.markers) {
        var newLayer = this.connectMerchantWithCustomer(this.markers[mId].marker, customerObj);

        //debugger;
        //debugger;
        // var clustMarker = L.marker([  ////const clustMarker = L.marker([
        //     this.markers[mId].marker.getLatLng().lat,
        //     this.markers[mId].marker.getLatLng().lng
        //     //getRandom(37, 39),
        //     //getRandom(-9.5, -6.5)
        // ]);
        //clusterMarkers.addLayer(this.markers[mId].marker);

        this.merchantsLayers.push(newLayer);
        //clusterMarkers.addLayer(newLayer);
        //this.merchantsLayers.push(newLayer);

        //clusterMarkers.addLayer(L.marker(newLayer));
    }
    //this.map.addLayer(clusterMarkers);
};

gl.functions.placesMap.prototype.showCourierByLatLngNew = function (waypoints) {
    //alert('showCourierByLatLngNew');
    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'yellow', fillColor: 'yellow'});
    var animatedMarker = L.animatedMarker(waypoints, {
        autoStart: true,
        icon: courierIcon,

        onEnd: function () {
            animatedMarker.setIcon(gl.functions.placesMap.prototype.icons.courierStand);
            //animatedMarker.setIcon(this.icons.courierStand);
        },

        distance: 30000, // meters
        interval: 100   // milliseconds
    });

    gl.data.worldMap.map.addLayer(animatedMarker);
};

gl.functions.placesMap.prototype.showCourierByLatLng = function (merchantLatLng) {

    var customerLatLng = gl.functions.getCurrentGeoLocation();

    // see https://stackoverflow.com/questions/34045265/destination-coordinates-in-leaflet-routing
    L.Routing.control({
        // YOUR STUFF
        //geocoder: L.Control.Geocoder.nominatim(),
        waypoints: [
            L.latLng(merchantLatLng.lat, merchantLatLng.lng),
            L.latLng(customerLatLng.lat, customerLatLng.lng),
        ],
        draggableWaypoints: false,
        fitSelectedRoutes: false,
        // отключает возможность изменять линию пути
        addWaypoints: false,
        createMarker: function (i, wp) {
            /*var options = {
                draggable: false,
                clickable: false,
                opacity: 0
            };
            return L.marker(wp.latLng, options);*/
        },
    }).on("routesfound", function (e) {
        if (this.routeIsFound) {
            return;
        } else {
            this.routeIsFound = true;
        }

        gl.functions.placesMap.prototype.showCourierByLatLngNew(e.routes[0].coordinates);
    }).addTo(this.map);
};
