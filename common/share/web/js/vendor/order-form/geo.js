/**
 * Географические типы и функции.
 *
 * Использующиеся расширения:
 * https://github.com/openplans/Leaflet.AnimatedMarker
 */

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

gl.functions.courierIconStart = function (coordinates) {
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
            //alert('onStart');
        },
        onEnd: function () {
            debugger;
            //alert('onEnd');
            // TODO: blow up this marker
            gl.data.worldMap.map.removeLayer(animatedMarker);
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
            //alert('onStart')
        },
        onEnd: function () {
            debugger;
            //alert('onEnd');
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

    this.merchantsLayer.selectFeaturesForPathDisplayById('origin_id', 0, true, 'SELECTION_NEW');
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

gl.functions.placesMap.prototype.showCourierByLatLngNew = function (waypoints) {
    var courierIcon = L.icon.pulse({iconSize: [11, 11], color: 'green', fillColor: 'yellow'});
    //var animatedMarker = L.animatedMarker(line.getLatLngs(), {
    var animatedMarker = L.animatedMarker(waypoints, {
        autoStart: true,
        icon: courierIcon,

        onStart: function () {
            //alert('onStart')
        },
        onEnd: function () {
            debugger;
            //alert('onEnd');
            // TODO: blow up this marker
            gl.data.worldMap.map.removeLayer(animatedMarker);
            alert('Finish !');
            //gl.data.worldMap.map.addLayer(gl.functions.placesMap.prototype.icons.courierStand);
        },

        //distance: 300,  // meters
        //interval: 2000, // milliseconds
        distance: 30000,      // meters
        interval: 1000   // milliseconds
    });

    gl.data.worldMap.map.addLayer(animatedMarker);
};

gl.functions.placesMap.prototype.showCourierByLatLng = function (merchantLatLng) {

    var customerLatLng = gl.functions.getCurrentGeoLocation();

    var x = L.Routing.control({
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
        //var coords = e.routes[0].coordinates;
        if (this.routeIsFound) {
            return;
        } else {
            this.routeIsFound = true;
        }

        debugger;
        //gl.functions.courierIconStart(waypoints);
        //gl.functions.placesMap.prototype.showCourierByLatLngNew(coords);
        gl.functions.placesMap.prototype.showCourierByLatLngNew(e.routes[0].coordinates);
    }).addTo(this.map);
};
