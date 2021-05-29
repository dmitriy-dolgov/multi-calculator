this.markers/**
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

gl.functions.courierIconStart_ver = function (coordinates) {
    /*if (coordinates.length > 2) {

    }*/
    debugger;
    gl.log(['coordinates2: ', coordinates]);

    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});

    //var coordinates = [];
    for (var i = 0; i < 2; ++i) {
        debugger;
        debugger;
        //alert('ewrip I:' + i);
        coordinatesMod.push([
                [
                    coordinates[i].latLng.lat,
                    coordinates[i].latLng.lng
                ]
            ]
        );
    }
};

/**
 * Получить координаты пути от shopLanLng до clientLanLng -
 * путь курьера с таким же направлением.
 *
 * @param shopLanLng
 * @param clientLanLng
 */
gl.functions.placesMap.prototype.getCourierPath = function (shopLanLng, clientLanLng) {

};

gl.functions.placesMap.prototype.showCourierByLatLng_new = function (shopLanLng) {

    var merchantLatLng = gl.functions.getCurrentGeoLocation();
    var popup = 'Курьер<img src="/img/courier/4.gif" style="width:20px">';

    debugger;
    debugger;
    // Здесь создаются иконки для кратчайшего пути.
    var routerControl = L.Routing.control({
        waypoints: [
            //[merchantLatLng.lat, merchantLatLng.lng],
            //[shopLanLng.lat, shopLanLng.lng]
            //L.latLng(57.74, 11.94),
            //L.latLng(57.6792, 11.949)
            L.latLng(merchantLatLng.lat, merchantLatLng.lng),
            L.latLng(shopLanLng.lat, shopLanLng.lng),
        ]
    }).addTo(gl.data.worldMap.map); //.bindPopup("Это описание курьера");

    // see https://stackoverflow.com/questions/34045265/destination-coordinates-in-leaflet-routing
    routerControl.on("routesfound", function (e) {
        debugger;
        debugger;
        console.log(["EEE: ", e]);
        var coordinates = e.routes[0].coordinates;
        console.log("coordinates:", coordinates);
        //var destination = coordinates[coordinates.length - 1];

        //debugger;
        debugger;
        gl.functions.courierIconStart(coordinates);
    });
};

gl.functions.courierIconStart = function (coordinates) {
//      debugger;
    gl.log(['coordinates: ', coordinates]);

    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});

    var line = L.polyline([
        [coordinates[0].lat, coordinates[0].lng],
        [coordinates[1].lat, coordinates[1].lng]
    ]);
    var animatedMarker = L.animatedMarker(line.getLatLngs(), {
        autoStart: true,
        icon: courierIcon,

        onStart: function () {
            alert('onStart')
        },
        onEnd: function () {
            debugger;
            //alert('onEnd');
            // TODO: blow up this marker
            gl.data.worldMap.map.removeLayer(animatedMarker);
            //alert('Finish !');
            //gl.data.worldMap.map.addLayer(gl.functions.placesMap.prototype.icons.courierStand);
        },

        //distance: 300,  // meters
        //interval: 2000, // milliseconds
        distance: 30000,      // meters
        interval: 1000   // milliseconds
    });

    gl.data.worldMap.map.addLayer(animatedMarker);
    return;

    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});

    var coordinatesMod = [];
    for (var i = 0; i < 2; ++i) {
        alert('99887');
        debugger;
        debugger;
        coordinatesMod.push([
                [
                    coordinates[i].lat,
                    coordinates[i].lng
                    //coordinates[i].latLng.lat,
                    //coordinates[i].latLng.lng
                ]
            ]
        );
    }

    debugger;
    debugger;
    var line = L.polyline(coordinatesMod);
    var animatedMarker = L.animatedMarker(line.getLatLngs(), {
        //distance: 300,    // meters
        //interval: 2000,   // milliseconds? looks like `second`
        distance: 5000,        // meters
        interval: 900000,  // milliseconds? looks like `second`
        autoStart: true,
        icon: courierIcon,
        onStart: function () {
            alert('onStart')
        },
        onEnd: function () {
            debugger;
            alert('onEnd');
            // TODO: blow up this marker
            gl.data.worldMap.map.removeLayer(animatedMarker);
            gl.data.worldMap.map.addLayer(gl.functions.placesMap.prototype.icons.courierStand);
        }
    });

    gl.data.worldMap.map.addLayer(animatedMarker);
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

gl.functions.placesMap.prototype.flyTo = function (lanLon) {
    this.map.flyTo(lanLon);
};

gl.functions.placesMap.prototype.allMarkers = [];
gl.functions.placesMap.prototype.allMovingMarkers = [];
gl.functions.placesMap.prototype.allPolylines = [];

gl.functions.placesMap.prototype.addMarkerByCoords = function (lat, lng, icon, popupHtml, extInfo) {
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

    if (extInfo) {
        newMarker.extInfo = extInfo;
    }

    /*newMarker.on('click', function (e) {
        gl.log('gl.functions.placesMap.globalZIndex 5: ' + gl.functions.placesMap.globalZIndex);
        newMarker.setZIndexOffset(++gl.functions.placesMap.globalZIndex);
    });*/

    this.allMarkers.push(newMarker);

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
                marker: this.addMarkerByCoords(markers[mId].latitude, markers[mId].longitude, icon, markers[mId].popupHtml, {idKey: markers[mId].id}),
            });
            // ++gl.functions.placesMap.globalZIndex;
            // gl.log('this.globalZIndex 2: ' + gl.functions.placesMap.globalZIndex);
        }
    }

    // gl.log('this.globalZIndex 1: ' + gl.functions.placesMap.globalZIndex);
    // this.customerMarker.setZIndexOffset(gl.functions.placesMap.globalZIndex);

    var allMarkersGroup = new L.featureGroup(this.allMarkers);
    this.map.fitBounds(allMarkersGroup.getBounds());

    this.connectAllMerchantsWithCustomer();
};

// /**
//  * Удалить все соединения и установить соединение от пиццерии до пользователя.
//  * TODO: connectAPizzeriaWithCustomer => connectAMerchantIdCustomer
//  *
//  *
//  * @param merchantId ID продавца (пиццерии)
//  */
// gl.functions.placesMap.prototype.connectAPizzeriaWithCustomer = function (merchantId) {
//     //alert('connectAPizzeriaWithCustomer ###########  gl.functions.placesMap.prototype');
//
//     //TODO: проверить надо ли this.merchantId
//     //this.merchantId = merchantId;
//
//     gl.log('connectAPizzeriaWithCustomer(), merchantId: ' + merchantId);
//
//     //TODO: здесь удаляются все изогнутые линии (коннекторы покупателя с продавцами)
//     if (this.merchantsLayer) {
//         this.map.removeLayer(this.merchantsLayer);
//         //TODO: надо ли?
//         this.merchantsLayer = null;
//     }
//
//     var geoJsonFeatureCollection = {
//         type: 'FeatureCollection',
//         features: []
//     };
//
//     var customerLatLon;
//     if (typeof this.customerMarker !== 'undefined') {
//         customerLatLon = this.customerMarker.getLatLng();
//     }
//
//     var coordinatesMod = [];
//
//     for (var mId in this.markers) {
//         gl.log('this.markers[mId].id: ' + this.markers[mId].id);
//
//         //debugger;
//         ////TODO: Ladlens comment - раскоммментить
//         if (this.markers[mId].id != merchantId) {
//             debugger;
//             continue;
//         }
//
//         debugger;   // пиццерия?
//         coordinatesMod.push([
//                 //TODO: не очень понятно с ._latlng - проверить, cделать правильно
//                 // this.markers[mId].marker.getLatLng(); - может лучше подходит?
//                 //markers[mId].latLng.lat,
//                 //coordinates[mId].latLng.lng,
//                 //]
//                 this.markers[mId].marker._latlng.lat,
//                 this.markers[mId].marker._latlng.lng
//             ]
//         );
//
//
//         //var mrkLanLng = this.markers[mId].marker.getLatLng();
//         //alert('connectAPizzeriaWithCustomer merchantId - gl.functions.placesMap.prototype');
//         //gl.data.worldMap.connectAPizzeriaWithCustomer(merchantId);
//
//         debugger;
//         var shopLanLng = this.markers[mId].marker.getLatLng();
//         //gl.functions.placesMap.prototype.showCourierByLatLng(shopLanLng);
//
//         break;
//     }
//     //return;
//
//     debugger;
//     var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});
//
//     var line = L.polyline(coordinatesMod);
//     //var animatedMarker = L.animatedMarker(line.getLatLngs(), {
//     debugger;
//     debugger;
//     var animatedMarker = L.animatedMarker(line.getLatLngs(), {
//         //distance: 300,    // meters
//         //interval: 2000,   // milliseconds? looks like `second`
//         distance: 1000,     // meters
//         interval: 900000,   // milliseconds? looks like `second`
//         autoStart: true,
//         icon: courierIcon,
//         onEnd: function () {
//             debugger;
//             alert('onEnd');
//             // TODO: blow up this marker
//             gl.data.worldMap.map.removeLayer(animatedMarker);
//             gl.data.worldMap.map.addLayer(gl.functions.placesMap.prototype.icons.courierStand);
//         }
//     }).addTo(this.map);
//
//     // if (this.merchantsLayer) {
//     //     this.merchantsLayer.selectFeaturesForPathDisplayById('origin_id', this.markers[mId].id, true, 'SELECTION_NEW');
//     // }
// };

/**
 * Установить соединение от продавца до пользователя.
 *
 * @param merchantId ID продавца
 * @param customerId ID пользователя TODO: !!!! - реализовать нормально, сейчас - костыль
 */
gl.functions.placesMap.prototype.connectMerchantWithCustomerVerDebug_1 = function (merchantId, customerLatLon) {
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

    this.merchantsLayer = L.canvasFlowmapLayer(geoJsonFeatureCollection, {
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

    //this.merchantsLayer.selectFeaturesForPathDisplayById('origin_id', 0, true, 'SELECTION_NEW');
};

/**
 * Установить соединение от продавца до пользователя.
 *
 * @param merchantId ID продавца
 * @param customerId ID пользователя TODO: !!!! - реализовать нормально, сейчас - костыль
 */
gl.functions.placesMap.prototype.connectAMerchantWithCustomer_old = function (merchantId, customerLatLon) {
    //TODO: что это
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
                // this.markers[mId].marker.getLatLng(); - может лучше подходит?
                //markers[mId].latLng.lat,
                //coordinates[mId].latLng.lng,
                //]
                this.markers[mId].marker._latlng.lat,
                this.markers[mId].marker._latlng.lng
            ]
        );


        //var mrkLanLng = this.markers[mId].marker.getLatLng();
        //alert('connectAPizzeriaWithCustomer merchantId - gl.functions.placesMap.prototype');
        //gl.data.worldMap.connectAPizzeriaWithCustomer(merchantId);

        debugger;
        var shopLanLng = this.markers[mId].marker.getLatLng();
        //gl.functions.placesMap.prototype.showCourierByLatLng(shopLanLng);

        break;
    }
    //return;

    debugger;
    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'red', fillColor: 'black'});

    var line = L.polyline(coordinatesMod);
    //var animatedMarker = L.animatedMarker(line.getLatLngs(), {
    //debugger;
    //debugger;
    var animatedMarker = L.animatedMarker(line.getLatLngs(), {
        //distance: 300,    // meters
        //interval: 2000,   // milliseconds? looks like `second`
        distance: 1000,     // meters
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

    // if (this.merchantsLayer) {
    //     this.merchantsLayer.selectFeaturesForPathDisplayById('origin_id', this.markers[mId].id, true, 'SELECTION_NEW');
    // }
};

/**
 * Удалить все соединения между покупателем и продавцами.
 */
gl.functions.placesMap.prototype.removeAllConnectionsBetweenCustomerAndMerchants = function () {
    //gl.log('evoke: removeAllConnectionsBetweenCustomerAndVendors()');

    if (this.merchantsLayer) {
        this.map.removeLayer(this.merchantsLayer);
        //TODO: надо ли?
        this.merchantsLayer = null;
    }
};

/**
 * Удалить все соединения и установить соединение от пиццерии до пользователя.
 * TODO: connectAPizzeriaWithCustomer => connectAMerchantIdCustomer
 *
 * @param merchantId ID продавца (пиццерии) TODO: реализовать для показа одиночной связи
 * @param customerLatLng ID пользователя TODO: !!!! - реализовать нормально, сейчас - костыль
 */
gl.functions.placesMap.prototype.connectAllMerchantsWithCustomerOldWithAnimation = function (customerLatLng) {
    alert('gl.functions.placesMap.prototype.connectAllMerchantsWithCustomer ##########');

    gl.log(['connectAPizzeriaWithCustomer(), merchantId: ', merchantId]);

    //TODO: какой-то косяк но пока ладно, до времени на рефакторинг
    if (!customerLatLng) {
        //if (typeof this.customerMarker !== 'undefined') {
        if (this.customerMarker) {
            customerLatLng = this.customerMarker.getLatLng();
        }
    }

    var geoJsonFeatureCollection = {
        type: 'FeatureCollection',
        features: []
    };

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
                // this.markers[mId].marker.getLatLng(); - может лучше подходит?
                //markers[mId].latLng.lat,
                //coordinates[mId].latLng.lng,
                //]
                this.markers[mId].marker._latlng.lat,
                this.markers[mId].marker._latlng.lng
            ]
        );


        //var mrkLanLng = this.markers[mId].marker.getLatLng();
        //alert('connectAPizzeriaWithCustomer merchantId - gl.functions.placesMap.prototype');
        //gl.data.worldMap.connectAPizzeriaWithCustomer(merchantId);

        debugger;
        var shopLanLng = this.markers[mId].marker.getLatLng();
        //gl.functions.placesMap.prototype.showCourierByLatLng(shopLanLng);

        break;
    }
    //return;

    debugger;
    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'white'});

    var line = L.polyline(coordinatesMod);
    //var animatedMarker = L.animatedMarker(line.getLatLngs(), {
    //debugger;
    debugger;
    var animatedMarker = L.animatedMarker(line.getLatLngs(), {
        //distance: 300,    // meters
        //interval: 2000,   // milliseconds? looks like `second`
        distance: 1000,     // meters
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

    // if (this.merchantsLayer) {
    //     this.merchantsLayer.selectFeaturesForPathDisplayById('origin_id', this.markers[mId].id, true, 'SELECTION_NEW');
    // }
};


/**
 * Пока-что координаты продавца и начальные координаты курьера совпадают.
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
    if (!customerObj) {
        customerObj = this.customerMarker;
    }
    var customerLatLon = customerObj.getLatLng();

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

    debugger;
    debugger;
    //55.92443935216234;37.704747925004
    var merchantLatLng = merchantObj.getLatLng();

    var geoJsonFeatureCollection = {
        type: 'FeatureCollection',
        features: []
    };

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
                "destination_id": merchantObj.extInfo.idKey,
                "destination_lon": merchantLatLng.lng,
                "destination_lat": merchantLatLng.lat
            }
        }
    );

    this.merchantsLayer = L.canvasFlowmapLayer(geoJsonFeatureCollection, {
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

    //if (this.merchantsLayer) {
    this.merchantsLayer.selectFeaturesForPathDisplayById('origin_id', 0, true, 'SELECTION_NEW');
    //}
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

    for (var mId in this.markers) {
        this.connectMerchantWithCustomer(this.markers[mId].marker, customerObj);
    }
};

gl.functions.placesMap.prototype.showCourierByLatLng_old_straingh_line = function (merchantLatLng) {
    var customerLatLng = gl.functions.getCurrentGeoLocation();

    var waypoints = [
        L.latLng(merchantLatLng.lat, merchantLatLng.lng),
        L.latLng(customerLatLng.lat, customerLatLng.lng),
    ];

    gl.functions.courierIconStart(waypoints);
};

gl.functions.placesMap.prototype.showCourierByLatLng = function (merchantLatLng) {

    alert('showCourierByLatLng');
    //customerLatLng
    var customerLatLng = gl.functions.getCurrentGeoLocation();
    debugger;
    debugger;
    var x = L.Routing.control({
        // YOUR STUFF
        //geocoder: L.Control.Geocoder.nominatim(),
        waypoints: [
            L.latLng(merchantLatLng.lat, merchantLatLng.lng),
            L.latLng(customerLatLng.lat, customerLatLng.lng),
            //L.latLng(57.74, 10.94),
            //L.latLng(56.6792, 11.949)
        ]
    }).addTo(this.map);

    var line = L.polyline(coordinatesMod);
    var animatedMarker = L.animatedMarker(line.getLatLngs(), {
        //distance: 300,    // meters
        //interval: 2000,   // milliseconds? looks like `second`
        distance: 1000,     // meters
        interval: 900000,   // milliseconds? looks like `second`
        autoStart: true,
        icon: courierIcon,
        onEnd: function () {
            debugger;
            alert('onEnd');
            //// TODO: blow up this marker
            //gl.data.worldMap.map.removeLayer(animatedMarker);
            //gl.data.worldMap.map.addLayer(gl.functions.placesMap.prototype.icons.courierStand);
        }
    }).addTo(this.map);

    //!!!!!!!!!!!!!!
    return;


    var waypoints = [];

    x.on("routesfound", function (e) {
        debugger;
        var waypoints = e.waypoints || [];
        var destination = waypoints[waypoints.length - 1]; // there you have the destination point between your hands

        debugger;
        gl.functions.courierIconStart(waypoints);
    });

    return;

    //debugger;
    var customerLatLng = gl.functions.getCurrentGeoLocation();
    //companyLatLng

    //$popup = 'Имя курьера<img src="/img/courier/4.gif" style="width:20px">';
    $popup = 'Курьер<img src="/img/courier/4.gif" style="width:20px">';

    /*var buyerLanLng = false;
    debugger;
    if (this.markers && this.markers.length !== 0) {
        buyerLatLng = this.markers[0];
    } else {
        return false;
    }*/

    // Здесь создаются иконки для кратчайшего пути.
    var routerControl = L.Routing.control({
        waypoints: [
            L.latLng(merchantLatLng.lat, merchantLatLng.lng),
            L.latLng(customerLatLng.lat, customerLatLng.lng),
        ]
    }).addTo(this.map); //.bindPopup("Это описание курьера");

    /*var trtl = this.courierMarker;
    var trtlThis = this;

    routerControl.on('leafletDirectiveMap.drag', function (event, args) {

        //get the Leaflet map from the triggered event.
        var map = args.leafletEvent.target;
        var center = map.getCenter();

        //update(recenter) marker
        $scope.vm.markers.mainMarker.lat = center.lat;
        $scope.vm.markers.mainMarker.lng = center.lng;
    });*/

    routerControl.on("routesfound", function (e) {
        //debugger;
        console.log("coordinates:", coordinates);
        var destination = coordinates[coordinates.length - 1];
        console.log("coordinates 2:", coordinates);
        console.log("destination 2:", destination);
        console.log("coordinates.length:", coordinates.length);
        console.log("coordinates:", coordinates);
    });

    gl.functions.courierIconStart(coordinates);
};
