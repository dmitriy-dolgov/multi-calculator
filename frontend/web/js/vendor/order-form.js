'use strict';

var totalAddedElements = 0;

var currentDragElement = null;

var componentPresenceFadeInOutTime = 200;

var userProfiles = gl.data.activeUserProfilesJs;

var userGeoPosition = gl.data.userGeoPosition;

var elems = {
    '.total-price': $('.total-price'),
    '.components-selected': $('.components-selected'),
    '.components-selected-details': $('.components-selected-details'),
    '#order-form .components-selected-details': $('#order-form .components-selected-details'),
    '.btn-order': $('.btn-order'),
    '.ingredients': $('.ingredients'),
    '.no-components-pane': $('.no-components-pane'),
    '.vertical-pane': $('.vertical-pane'),
    '.capt-price': $('.capt-price'),
    '.component-holder': $('.component-holder'),
    '.video': $('.video'),
    '#elems-container': $('#elems-container'),
    '#order-form-submit': $("#order-form-submit"),
    '.vertical-pane .component': $('.vertical-pane .component'),
    '.vertical-pane .component .img-wrap': $('.vertical-pane .component .img-wrap'),
    '.customer-orders-panel': $('.customer-orders-panel'),
    '.wrp-pane .btn-head': $('.wrp-pane .btn-head'),
    //'.categories-panel.btn-head': $('.categories-panel.btn-head'),
    '.categories-panel.panel-elements-list': $('.categories-panel.panel-elements-list'),
    '.components-in-stock': $('.components-in-stock'),
    '.components-in-stock .collapse-content': $('.components-in-stock .collapse-content')
};

gl.functions.setLogged = function () {
    location.reload();
};
