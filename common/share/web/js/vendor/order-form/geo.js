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
    //var coords = {lat: 55.107540130615, lng: 33.267589569092};     // Сафоново?

    //dateandtime.info/ru/citycoordinates.php?id=524901
    var coords = {lat: 55.7522200, lng: 37.6155600};     // Москва
//@55.097572,33.2094561,12.25
//@55.7522200,37.6155600
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            coords.lat = position.coords.latitude;
            coords.lng = position.coords.longitude;
            // if (gl.data.worldMap) {
            //     if (gl.data.worldMap.courierMarker) {
            //         gl.data.worldMap.map.removeLayer(gl.data.worldMap.courierMarker);
            //     }
            // }
        });
    }

    return coords;
};

//---- Bootstrap functions -------------------------
gl.functions.correctGeolocation();
