/**
 * Географические типы и функции.
 */
//alert('FROM');

/**
 * Продавец (пиццерия, статические координаты).
 */
gl.getObject('orderPanel.vendor.geoInfo');

/**
 * Продавец (пиццерия, маркер для курьера).
 */
gl.getObject('orderPanel.vendor.courierMarker');

/**
 * Заказчик (покупатель, произвольные координаты)
 */
gl.getObject('orderPanel.customer.geoInfo');

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

/**
 * Текущее местоположение курьера.
 *
 * @returns {{lng: number, lat: number}}
 */
gl.functions.getCurrentGeoLocation = function () {
    var coords = {lat: 55.107540130615, lng: 33.267589569092};

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
                //alert('icons.courier IS HERE 1');
                //gl.data.worldMap.courierMarker
                //    = gl.data.worldMap.addMarkerByCoords(coords.lat, coords.lng, gl.data.worldMap.icons.courier);
                //gl.data.worldMap.flyTo([coords.lat, coords.lng]);
            }
        });
    }

    gl.log('getCurrentGeoLocation return');
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

    //var mrkLanLng = L.latLng(initialMapParameters.latitude, initialMapParameters.longitude);

    if (this.markers) {
        for (var mId in this.markers) {
            mrkLanLng = this.markers[mId].marker.getLatLng();
            break;
        }
        if (this.markers && this.markers[0]) {
            this.showCourierByLatLng(mrkLanLng);
        }
    }
};

gl.functions.courierIconStart = function (coordinates) {
    debugger;
    gl.log(['coordinates2: ', coordinates]);

    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});

    //var coordinates = [];
    for (var i = 0; i < 2; ++i) {
        debugger;
        debugger;
        alert('ewrip I:' + $i);
        coordinatesMod.push([
                [
                    coordinates[i].latLng.lat,
                    coordinates[i].latLng.lng
                ]
            ]
        );
    }
};

gl.functions.placesMap.prototype.showCourierByLatLng = function (shopLanLng) {

    var merchantLatLng = gl.functions.getCurrentGeoLocation();
    var popup = 'Курьер<img src="/img/courier/4.gif" style="width:20px">';

    debugger;
    // Здесь создаются иконки для кратчайшего пути.
    var routerControl = L.Routing.control({
        waypoints: [
            [merchantLatLng.lat, merchantLatLng.lng],
            [shopLanLng.lat, shopLanLng.lng]
        ]
    }).addTo(gl.data.worldMap.map); //.bindPopup("Это описание курьера");

    // see https://stackoverflow.com/questions/34045265/destination-coordinates-in-leaflet-routing
    routerControl.on("routesfound", function (e) {
        debugger;
        console.log(["EEE: ", e]);
        var coordinates = e.routes[0].coordinates;
        console.log("coordinates:", coordinates);
        //var destination = coordinates[coordinates.length - 1];

        debugger;
        debugger;
        gl.functions.courierIconStart(coordinates);
    });
};

// gl.functions.courierIconStartOld = function (coordinates) {
// //      debugger;
//     gl.log(['coordinates: ', coordinates]);
//
//     var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});
//
//     var coordinatesMod = [];
//     for (var i = 0; i < 2; ++i) {
//         debugger;
//         debugger;
//         alert('99887');
//         coordinatesMod.push([
//                 [
//                     coordinates[i].latLng.lat,
//                     coordinates[i].latLng.lng
//                 ]
//             ]
//         );
//     }
//
//     debugger;
//     var line = L.polyline(coordinatesMod);
//     var animatedMarker = L.animatedMarker(line.getLatLngs(), {
//         //distance: 300,    // meters
//         //interval: 2000,   // milliseconds? looks like `second`
//         distance: 5000,        // meters
//         interval: 900000,  // milliseconds? looks like `second`
//         autoStart: true,
//         icon: courierIcon,
//         //onStart: function () {alert('onStart')},
//         onEnd: function () {
//             debugger;
//             alert('onEnd');
//             // TODO: blow up this marker
//             gl.data.worldMap.map.removeLayer(animatedMarker);
//             gl.data.worldMap.map.addLayer(gl.functions.placesMap.prototype.icons.courierStand);
//         }
//     });
//
//     gl.data.worldMap.map.addLayer(animatedMarker);
// };


gl.functions.placesMap.prototype.hideCourier = function () {
    //var latLng = gl.functions.getCurrentGeoLocation();
    //this.courierMarker = this.addMarkerByCoords(latLng.lat, latLng.lng, this.icons.courier);
};

gl.functions.placesMap.prototype.moveCustomerMarker = function (newLan, newLgn) {
    var newLatLng = new L.LatLng(newLan, newLgn);
    this.customerMarker.setLatLng(newLatLng);
};

gl.functions.placesMap.prototype.flyTo = function (lanLon) {
    this.map.flyTo(lanLon);
};

gl.functions.placesMap.prototype.allMarkers = [];
gl.functions.placesMap.prototype.allMovingMarkers = [];
gl.functions.placesMap.prototype.allPolylines = [];

gl.functions.placesMap.prototype.addMarkerByCoords = function (lat, lng, icon, popupHtml) {
    var newMarker;

    //debugger;
    var latLng = L.latLng(lat, lng);
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
    '/img/courier/8DL5.gif'
    /*'/img/map/courier-moto.png',
    '/img/courier/1.jpg',
    '/img/courier/2.png',
    '/img/courier/3.jpg',
    '/img/courier/4.gif',*/
];

//glIconUrl = glIconUrl[0 + Math.floor((5 - 0) * Math.random())];
glIconUrl = glIconUrl[0];

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
 * TODO: connectAPizzeriaWithCustomer => connectAMerchantIdCustomer
 *
 *
 * @param merchantId ID продавца (пиццерии)
 */
gl.functions.placesMap.prototype.connectAPizzeriaWithCustomer = function (merchantId) {
    //alert('connectAPizzeriaWithCustomer ###########  gl.functions.placesMap.prototype');

    this.merchantId = merchantId;

    //gl.log('connectAPizzeriaWithCustomer(), merchantId: ' + merchantId);
    console.log('connectAPizzeriaWithCustomer(), merchantId: ' + merchantId);

    //debugger;
    //TODO: здесь удаляются все изогнутые линии (коннекторы покупателя с пиццериями
    if (this.flowmapLayer) {
        this.map.removeLayer(this.flowmapLayer);
        //TODO: надо ли?
        this.flowmapLayer = null;
    }

    var geoJsonFeatureCollection = {
        type: 'FeatureCollection',
        features: []
    };

    var customerLatLon;
    if (typeof this.customerMarker !== 'undefined') {
        customerLatLon = this.customerMarker.getLatLng();
    }

    var coordinatesMod = [];

    for (var mId in this.markers) {
        gl.log('this.markers[mId].id: ' + this.markers[mId].id);

        //debugger;
        ////TODO: Ladlens comment - раскоммментить
        if (this.markers[mId].id != merchantId) {
            debugger;
            continue;
        }

        debugger;   // пиццерия?
        coordinatesMod.push([
                //TODO: не очень понятно с ._latlng - проверить, cделать правильно
                //markers[mId].latLng.lat,
                //coordinates[mId].latLng.lng,
                //]
                this.markers[0].marker._latlng.lat,
                this.markers[0].marker._latlng.lng
            ]
        );


        //var mrkLanLng = this.markers[mId].marker.getLatLng();
        //alert('connectAPizzeriaWithCustomer merchantId - gl.functions.placesMap.prototype');
        //gl.data.worldMap.connectAPizzeriaWithCustomer(merchantId);

        var shopLanLng = this.markers[mId].marker.getLatLng();
        gl.functions.placesMap.prototype.showCourierByLatLng(shopLanLng);

        break;
    }
    //return;

    debugger;
    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});

    var line = L.polyline(coordinatesMod);
    var animatedMarker = L.animatedMarker(line.getLatLngs(), {
        //distance: 300,    // meters
        //interval: 2000,   // milliseconds? looks like `second`
        distance: 50,       // meters
        interval: 900000,   // milliseconds? looks like `second`
        autoStart: true,
        icon: courierIcon,
        onEnd: function () {
            debugger;
            alert('onEnd');
            // TODO: blow up this marker
            gl.data.worldMap.map.removeLayer(animatedMarker);
            gl.data.worldMap.map.addLayer(gl.functions.placesMap.prototype.icons.courierStand);
        }
    }).addTo(this.map);

    this.flowmapLayer.selectFeaturesForPathDisplayById('origin_id', this.markers[mId].id, true, 'SELECTION_NEW');
};

/**
 * Пока-что координаты магазина и начальные координаты курьера совпадают.
 *
 * @param merchantLatLng коотдинаты продавца.
 */
gl.functions.placesMap.prototype.connectStoreWithCustomer = function (merchantLatLng) {

    var customerLatLng = gl.functions.getCurrentGeoLocation();

    var $popup = 'Курьер<img src="/img/courier/4.gif" style="width:20px">';

    debugger;

    // Здесь создаются иконки для кратчайшего пути.
    var routerControl = L.Routing.control({
        waypoints: [
            [merchantLatLng.lat, merchantLatLng.lng],
            [customerLatLng.lat, customerLatLng.lng],
        ]
    }).addTo(this.map).bindPopup($popup);


    // see https://stackoverflow.com/questions/34045265/destination-coordinates-in-leaflet-routing
    routerControl.on("routesfound", function (e) {
        /*debugger;
        gl.log(["EEE: ", e]);
        var coordinates = e.routes[0].coordinates;
        console.log("coordinates:", coordinates);
        var destination = coordinates[coordinates.length - 1];
        console.log("coordinates 2:", coordinates);
        console.log("coordinates.length:", coordinates.length);
        console.log("destination 2:", destination);*/

        console.log("coordinates:", coordinates);

        debugger;
        debugger;
        gl.functions.courierIconStart(coordinates);
    });

    //var customerLatLng = 'sdf';
};
