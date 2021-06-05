gl.functions.correctGeolocation = function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            //var lat = position.coords.latitude;
            //var lng = position.coords.longitude;
            var lat = 55.7522200;   //position.coords.latitude;
            var lng = 37.6155600;    //position.coords.longitude;
            if (gl.data.worldMap) {
                gl.data.worldMap.flyTo([lat, lng]);
            }
        });
    } else {
        alert(gl.data['geolocation-is-not-accessible']);
    }
};

gl.functions.placesMap = function (id, initialMapParameters) {
    this.map = L.map(id).setView([initialMapParameters.latitude, initialMapParameters.longitude], initialMapParameters.zoom);

    var pulsingIcon = L.icon.pulse({iconSize: [15, 15], color: 'green', fillColor: 'red'});

    var lat = 55.7522200;   //position.coords.latitude;
    var lng = 37.6155600;    //position.coords.longitude;
    this.customerMarker = this.addMarkerByCoords(lat, lng, pulsingIcon);
    this.customerMarker = this.addMarkerByCoords(initialMapParameters.latitude, initialMapParameters.longitude, pulsingIcon);

    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(this.map);
};

gl.functions.placesMap.prototype.flyTo = function (lanLon) {
    this.map.flyTo(lanLon);
};

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
        var lat = 55.7522200;   //position.coords.latitude;
        var lng = 37.6155600;    //position.coords.longitude;

        gl.data.worldMap = new gl.functions.placesMap('worker-place-map', {latitude: lat, longitude: lng, zoom: 16});
    });
} else {
    alert(gl.data['geolocation-is-not-accessible']);
}

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

    return newMarker;
};

gl.functions.correctGeolocation();

setInterval(function () {
    gl.functions.correctGeolocation();
}, 1000);
