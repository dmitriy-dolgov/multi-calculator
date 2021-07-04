/**
 * Географические типы и функции.
 *
 * Использующиеся расширения:
 * https://github.com/openplans/Leaflet.AnimatedMarker
 */

gl.getObject('orderPanel.customer.geoInfo');

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


gl.functions.correctGeolocation = function () {
    //gl.log('correctGeolocation before');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            //gl.log('correctGeolocation IN FUNCTION');
            //var lat = 55.7522200;   //position.coords.latitude;
            //var lng = 37.6155600;   //position.coords.longitude;
            var lat = 55.7522200;
            var lng = 37.6155600;

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
    var coords = {lat: 55.107540130615, lng: 33.267589569092};     // Сафоново?

    //dateandtime.info/ru/citycoordinates.php?id=524901
    //var coords = {lat: 55.7522200, lng: 37.6155600};     // Москва

    // Убрать (закомментить) в рабочем режиме
    //return coords;

    //@55.097572,33.2094561,12.25
    //@55.7522200,37.6155600
    try {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                coords.lat = position.coords.latitude;
                coords.lng = position.coords.longitude;
                // /*if (gl.data.worldMap) {
                //     if (gl.data.worldMap.courierMarker) {
                //         gl.data.worldMap.map.removeLayer(gl.data.worldMap.courierMarker);
                //     }
                // }*/

                console.log('coords: ' + coords);

                gl.functions.placesMap.prototype.flyTo(coords);
            })
        }
    } catch (e) {
        console.log('navigator.geolocation');
    }

    return coords;
};

/**
 * Текущее местоположение курьера.
 *
 * @returns {{lng: number, lat: number}}
 */
gl.functions.getCurrentGeoLocationOld = function () {
    //dateandtime.info/ru/citycoordinates.php?id=524901
    //var coords = {lat: 55.7522200, lng: 37.6155600};     // Москва
    var coords = {lat: 55.107540130615, lng: 33.267589569092};     // Сафоново
    //var coords = {lat: 59.9386, lng: 30.3141};     // СПБ
    //var coords = {lat: 56.4364999, lng: 37.00252594};   // Москва - север (Руслана положение)


    // Убрать (закомментить) в рабочем режиме
    //return coords;

    debugger;

    //@55.097572,33.2094561,12.25
    //@55.7522200,37.6155600

    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };

    try {
        debugger;
        if (navigator.geolocation) {
            //alert('navigator.geolocation');
            debugger;
            navigator.geolocation.getCurrentPosition(function (position) {
                    //alert('navigator.geolocation.getCurrentPosition');
                    debugger;

                    if (position) {

                        if (position.coords) {
                            var crd = position.coords;
                            //alert('pos.coords: ' + pos.coords);

                            coords.lat = position.coords.latitude;
                            coords.lng = position.coords.longitude;

                            console.log('Ваше текущее местоположение:');
                            console.log(`Широта: ${crd.lat}`);
                            console.log(`Долгота: ${crd.lng}`);
                            console.log(`Плюс-минус ${crd.accuracy} метров.`);

                            gl.functions.placesMap.prototype.flyTo(coords);
                        } else {
                            alert('NO position.coords');

                            // /*if (gl.data.worldMap) {
                            //     if (gl.data.worldMap.courierMarker) {
                            //         gl.data.worldMap.map.removeLayer(gl.data.worldMap.courierMarker);
                            //     }
                            // }*/

                            //console.log('coords: ' + coords);
                        }

                    } else {
                        alert('NO position');
                    }

                }, function (err) {
                    //alert('err');
                    debugger;
                    console.warn(`ERROR(${err.code}): ${err.message}`);
                }, options
            );

        } else {
            //TODO: убрать после теста
            alert('NONONO navigator.geolocation NONONO');
            debugger;
        }
    } catch (e) {
        console.log('navigator.geolocation');
        debugger;
        //alert('NONONO (catch)');
    }

    return coords;
};

//---- Bootstrap functions -------------------------
gl.functions.correctGeolocation();
