/**
 * placesMap object
 */

function getRandom(min, max) {
    return Math.random() * (max - min) + min;
}

//TODO: не нужно?
//gl.functions.placesMap.prototype.flyTo = function (lanLon) {
gl.getObject('functions.placesMap').prototype.flyTo = function (lanLon) {
    if (this.map && this.map.flyTo) {
        this.map.flyTo(lanLon);
    }
};

gl.functions.getGeo = function () {

    setInterval(function () {
        //debugger;
        var lanLon = gl.functions.getCurrentGeoLocation();
        //this.courierMarker = this.addMarkerByCoords(latLng.lat, latLng.lng, this.icons.courier);
        $('#time-elapsed').text('Новые координаты:' + JSON.stringify(lanLon));

        //var coords = [lanLon[lanLon][lat]], lng: 37.6155600}];

        gl.functions.placesMap.prototype.flyTo(lanLon);
    }, 4000);
};

gl.functions.getGeo();

gl.functions.placesMap = function (id, initialMapParameters) {
    this.map = L.map(id).setView([initialMapParameters.latitude, initialMapParameters.longitude], initialMapParameters.zoom);

    var mapCopy = this.map;

    this.map.on('zoomend', function () {

        //var mapCopy = this.map;
        //var currentZoom = mapCopy.getZoom();

        //$('.leaflet-marker-icon').each(function () {
        $('.leaflet-marker-icon.leaflet-zoom-animated.leaflet-interactive').each(function (e, e2, e3, e4) {
            debugger;   // ACT

            var markerObj = $(this);

            if (markerObj[0] && markerObj[0].extInfo) {
                alert('O SIZE pe+++++++++++++++++++++++++++');
            }

            //if (!(markerObj.extInfo && markerObj.extInfo[0] && markerObj.extInfo[0].)) {
            if (!(markerObj[0] && markerObj[0].extInfo && markerObj[0].extInfo.doNotResize)) {
                //console.log('(mapCopy.getZoom() * 3 + 3)', (mapCopy.getZoom() * 3 + 3));
                markerObj.css('width', (mapCopy.getZoom() * 3 + 3) + 'px');
                markerObj.css('height', 'auto');
                //alert('Check');
            } else {
                alert('NO SIZE');
            }

            return false;
        });
    });

    debugger;   // stop for markrer
    // [initialMapParameters.latitude, initialMapParameters.longitude
    // это маркер покупателя, координаты Москвы для теста
    //
    this.customerMarker = addMarkerByCoords()
    {
        debugger;

        initialMapParameters.latitude,
            initialMapParameters.longitude,
            //55.7522200,
            //37.6155600,
            L.icon.pulse({iconSize: [15, 15], color: 'black', fillColor: 'yellow'}),
            false,
            {doNotResize: true}
    }

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© 1 - OpenStreetMap contributors',   //'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(this.map);

//var mrkLatLng = L.latLng(initialMapParameters.latitude, initialMapParameters.longitude);

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

        debugger;
        //TODO: !!! всегда выбирается первый магазин - сделать чтобы выбирался принявший заказ !!!
        for (var mId in this.markers) {
            mrkLatLng = this.markers[mId].marker.getLatLng();
            break;
        }
        // TODO:
        // if (this.markers && this.markers[0]) {
        //     //alert('this.markers');
        //     debugger;
        //     this.showCourierByLatLng(mrkLatLng);
        // }
    } else {
        //TODO: удалить закомментированное если нигде не используется.

        alert('TODO: удалить закомментированное если нигде не используется.');

        //alert('if (this.markers) { - ELSE');
        //gl.log(['HERE === this.markers:', this.markers]);
        //console.log('HERE === this.markers:', this.markers);
    }


    //gl.functions.placesMap.prototype.allMarkers = [];
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
        alert('gl.functions.placesMap.prototype.moveCustomerMarker');
        var newLatLng = new L.LatLng(newLan, newLgn);
        this.customerMarker.setLatLng(newLatLng);
    };

    //TODO: не нужно?
    // gl.functions.placesMap.prototype.flyTo = function (lanLon) {
    //     if (this.map && this.map.flyTo) {
    //         this.map.flyTo(lanLon);
    //     }
    // };

    gl.functions.placesMap.prototype.addMarkerByCoords = function (lat, lng, icon, popupHtml, extInfoOrig) {

        gl.log('gl.functions.placesMap.prototype.addMarkerByCoords() !!!!!!!!!!!!!!!!!!!!');
        gl.log('INFO: ', extInfoOrig);

        if (!extInfoOrig) {
            extInfoOrig = {};
            //return;
        }

        // debugger;
        // debugger;
        // debugger;
        console.log("lat:", lat);
        console.log("lng:", lng);
        //var latLng = L.latLng(lat, lng);
        var latLng = L.latLng(lat, lng);
        //var extInfoMod = {};

        // for (var key in extInfoOrig) {
        //     if (extInfoOrig.hasOwnProperty(key)) {
        //         extInfoMod[key] = extInfoOrig[key];
        //     }
        // }

        console.log('alert(latLng)', latLng);

        //var newMarker = new L.marker(latLng, extInfoMod);
        var newMarker = new L.marker(latLng, extInfoOrig);

        if (popupHtml) {
            newMarker.bindPopup(popupHtml);
        } else {
            newMarker.bindPopup('No html popup !!!');
        }

        //debugger;
        newMarker.addTo(this.map);

        /*newMarker.on('click', function (e) {
            gl.log('gl.functions.placesMap.globalZIndex 5: ' + gl.functions.placesMap.globalZIndex);
            newMarker.setZIndexOffset(++gl.functions.placesMap.globalZIndex);
        });*/

        //TODO: проверить закомментированое!!!
        //debugger;
        //this.allMarkers.push(newMarker);

        return newMarker;
    };

    // Главная функция наполнения this.markers
    // По идее this.markers должна содержать ВСЕ маркеры.
    //gl.functions.placesMap.prototype.addMarkersToMap = function (markerInfo, toResize) {
    gl.functions.placesMap.prototype.addMarkersToMap = function (markerInfo) {

        gl.log('gl.functions.placesMap.prototype.addMarkersToMap()');

        //this.markers = [];
        //if (markerInfo.length && toResize) {
        if (markerInfo.length) {

            var clusteredMarkers = L.markerClusterGroup();

            if (!this.markers) {
                this.markers = [];
            }

            for (var mId in markerInfo) {

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

                //this.markers  = [];

                clusteredMarkers.addLayer(newMarker);
                //this.allMarkers.push(newMarker);
            }
        }

        // ???? //this.allMarkers.push(newMarker);

        this.map.addLayer(clusteredMarkers);

        // gl.log('this.globalZIndex 1: ' + gl.functions.placesMap.globalZIndex);
        // this.customerMarker.setZIndexOffset(gl.functions.placesMap.globalZIndex);

        //var allMarkersGroup = new L.(this.allMarkers);
        //var markersGroup = new L(this.markers);   // uneccessary allMarkersGroup
        //this.map.fitBounds(markersGroup.getBounds());  // добавит ли this.connectAllMerchantsWithCustomer() к ширине ???
        //    var markersGroup = new L(this.markers);   // uneccessary allMarkersGroup
        //    this.map.fitBounds(markersGroup.getBounds());  // добавит ли this.connectAllMerchantsWithCustomer() к ширине ???

        this.connectAllMerchantsWithCustomer();

        /*var markersGroup = new L(this.markers);
        L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.'),*/

        /*var markersGroup = new L(this.markers);
        this.map.fitBounds(markersGroup.getBounds());*/

        //this.map.fitBounds(allMarkersGroup.getBounds());
        //this.map.fitBounds(this.markers.getBounds());

        //TODO: Вынести это в общее место
        //STAY here !!!!!!!!!
        // if (this.markers) {
        // {
        //     var lGgroup = [];
        //     for (var key in this.markers) {
        //         var mrkElem = this.markers[key];
        //         lGgroup.push();
        //     }
        //         //var markersGroup = new L(this.markers)
        //         this.markers[key] = '';
        //     }
        //     this.map.fitBounds(lGgroup.getBounds());
        //     var lGgroup = L.layerGroup(this.markers);
        //
        //     var lGgroup = L.layerGroup(this.markers);
        //     this.map.fitBounds(lGgroup.getBounds());
        // }
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

        //alert('gl.functions.placesMap.prototype.removeAllConnectionsBetweenCustomerAndMerchants');
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
     *  TODO: merchantData => merchantObj
     *
     * @param merchantData данные точки продавца.
     * @param customerObj объект пользователя (opt.).
     */
    gl.functions.placesMap.prototype.connectMerchantWithCustomerRealPath = function (merchantData, customerObj) {
        if (!customerObj) {
            customerObj = this.customerMarker;
        }

        var merchantLatLng = gl.getObject('map.coordinates').string2array(merchantData.company_lat_long);
        var customLatLng = gl.getObject('map.coordinates').string2array(customerObj.company_lat_long);

        debugger;   //waypoints

        $tmpV = L.Routing.control({
            ////TODO: merchantLatLng, _latlng ??
            waypoints: [
                // test values
                //L.latLng(55.92443935216234, 37.7047479250041),
                //L.latLng(56.92443935216234, 38.7047479250041)

                L.latLng(merchantLatLng),
                L.latLng(customLatLng)

                //L.latLng(merchantLatLng.lat, merchantLatLng.lng);
                //L.latLng(customerObj.lat, customerObj.lng)
                //L.latLng(merchantLatLng.lat, merchantLatLng.lng),
                //merchantLatLng,
                //L.latLng(customerObj._latlng.lat, customerObj._latlng.lng)
                //customerObj._latlng
            ]
        });

        debugger;
        $tmpV.addTo(this.map);

        //TODO: customerLatLon - где используется ???
        //var customerLatLon = customerObj.getLatLng();

        //var merchantLatLng = gl.getObject('map.coordinates').string2array(merchantData.company_lat_long);

        //var merchantLNObrj = L.latLng(merchantData.lat, merchantData.lng);
        //var merchantLatLng = gl.getObject('map.coordinates').string2array(merchantData.company_lat_long);
        //var merchantLatLng = gl.getObject('map.coordinates').string2array(merchantData.company_lat_long);

        //debugger;
        //debugger;
        //debugger;
        //this.showCourierByLatLng(merchantLatLng);
        //this.showCourierByLatLng(L.latLng(merchantData.lat, merchantData.lng));
        this.showCourierByLatLng(merchantLatLng);


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

        debugger
        ('connectMerchantWithCustomer[]');

        if (!customerObj) {
            //customerObj = this.customerMarker;
            //customerObj = this.customerMarker['_lnglat'];
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
        //console.log('merchantObj', merchantObj);
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


        /**
         * TODO: удалить если НЕ будет использоваться.
         *
         * Нахождение кратчайшего пути.
         *
         * Новая (тестовая версия). С прежней.
         * gl.functions.placesMap.prototype.showCourierByLatLng
         *
         * // @param waypoints
         * @param merchantLatLng
         */
        gl.functions.placesMap.prototype.showCourierByLatLng = function (merchantLatLng, customerLatLng) {
            /*alert('showCourierByLatLngNew }}}}}}}}}}}}}}}}}}}');
            debugger;*/
            //console.log('waypoints:', waypoints);

            debugger;   // stop here
            //merchantLatLng = L.latLng(merchantLatLng.lat, merchantLatLng.lng);

            //if (!customerLatLng) {
            if (1) {

                /*debugger;
                customerLatLng = this.customerMarker._latlng;

                debugger;
                //var courierIcon = L.icon.pulse({iconSize: [15, 15], color: 'black', fillColor: 'red'});
                var line = L.polyline([
                    [
                        merchantLatLng.lat,     //55.580748,
                        merchantLatLng.lng      //36.8251138
                    ],
                    [
                        customerLatLng.lat,
                        customerLatLng.lng

                ]);*/

                alter('var courierIcon');
                debugger;   // var courierIcon

                var courierIcon = L.icon.pulse({iconSize: [15, 15], color: 'black', fillColor: 'red'});
                var line = L.polyline([[40.68510, -73.94136], [40.68576, -73.94149], [40.68649, -73.94165]]),
                    animatedMarker = L.animatedMarker(line.getLatLngs(), {
                        autoStart: true,
                        icon: courierIcon,
                        onEnd: function () {
                            alert('onEnd pulse ++++++++++++++++++++!!!!!');
                            debugger;
                            animatedMarker.setIcon(gl.functions.placesMap.prototype.icons.courierStand)
                            //animatedMarker.setIcon(this.icons.courierStand);
                        },
                        distance: 500,   // meters
                        interval: 10000   // milliseconds
                    });

                debugger;

                return;


                //var line = L.polyline([[40.68510, -73.94136],[40.68576, -73.94149],[40.68649, -73.94165]]),
                //animatedMarker = L.animatedMarker(line.getLatLngs());

                //map.addLayer(animatedMarker);

                //(gl.data.worldMap.map);
                /*gl.data.worldMap.map.addLayer(customerLatLng);

                var animatedMarker = L.animatedMarker(line.getLatLngs(), {
                    distance: 300,  // meters
                    interval: 2000, // milliseconds
                });*/


                //Тест
                /*customerLatLng = {
                    'lat': '55.7522200',
                    'lon': '36.461324',
                    'lng': '36.461324'
                };*/

                // debugger;
                // customerLatLng = L.latLng(52.7522200, 37.461324);
                // debugger;

                // customerLatLng = this.customerMarker._latlng;
                // if (!customerLatLng) {
                //     debugger;
                //     debugger;
                //     alert('customerLatLng');
                //     debugger;
                //     debugger;
                //
                //     //Тест
                //     customerLatLng = {
                //         'lat': '55.7522200',
                //         'lon': '36.461324',
                //         'lng': '36.461324'
                //     };  //gl.getObject('map.coordinates').string2array(customerLatLng.company_lat_long);
                // }
            }

            var $rConrol = L.Routing.control({
                waypoints: [
                    //L.latLng(55.92443935216234, 37.7047479250041),
                    //L.latLng(56.92443935216234, 38.7047479250041)
                    //L.latLng(merchantLatLng.lat, merchantLatLng.lng),
                    //L.latLng(waypoints.lat, waypoints.lng)
                    //merchantLatLng,
                    //customerLatLng
                ]
            });     //.addTo(gl.data.worldMap.map); //.getWaypoints();

        };
    };
};
