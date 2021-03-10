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

$(function () {
    setTimeout(function () {
        $('.create-pizza').addClass('move-to-rb');
    }, 5000);
});


// Dummy code: if there is no web
if (!window.L) {
    window.L = {
        icon: function () {
        },
        Icon: {},
        Marker: {
            extend: function () {

            }
        },
        marker: {},
        DivIcon: {
            extend: function () {

            }
        }
    };
}

$('.menu-unwrap-panel .menu-unwrap-button').click(function () {
    $(this).parent().toggleClass('folded');
    $('.unwrapped-panel').removeClass('unwrap');

    gl.container.localStorage.addItem('user_interface',
        {'.menu-unwrap-panel': {add: false, '.menu-unwrap-panel': 'folded'}}
    );

});

$('.menu-item.pizzas').click(function () {
    $('.unwrapped-panel').removeClass('unwrap');
    $('.pizzas-panel-elements-list').toggleClass('unwrap');
    //gl.funcContainer.storage.uiState()

    gl.container.localStorage.addItem('user_interface',
        {'.pizzas-panel-elements-list': {add: true, className: 'unwrap'}}
    );
});

$('.menu-item.orders').click(function () {
    $('.unwrapped-panel').removeClass('unwrap');
    $('.orders-panel-elements-list').toggleClass('unwrap');

    gl.container.localStorage.addItem('user_interface',
        {'.orders-panel-elements-list': {add: true, className: 'unwrap'}}
    );
});

$('.menu-item.you').click(function () {
    $('.unwrapped-panel').removeClass('unwrap');
    $('.you-panel-elements-list').toggleClass('unwrap');

    gl.container.localStorage.addItem('user_interface',
        {'.you-panel-elements-list': {add: true, className: 'unwrap'}}
    );
});

if (!gl.container) {
    gl.container = {};
}

if (!gl.fContainer) {
    gl.fContainer = {};
}

/**
 * Удаление лишних пробелов.
 * TODO: Возможно, и других элементов типа двойных пробелов.
 *
 * @param data
 * @returns {*}
 */
gl.functions.refineObjectValuesData = function (data) {
    //return String.trim(data);
    return data;
};

/**
 * Получение значения элемента и его очистка от ненежных элементов.
 *
 * @param parentElem
 * @param className
 * @param elemName
 * @returns {*}
 */
// gl.functions.setAndGetRefinedDataToElement = function (parentElemSelector, className, elemName) {
//     //TODO: parentElemSelector - рассмотреть добавление в кеш
//     var elemObj = $(parentElemSelector).find('input[name="' + className + '[' + elemName + ']"]');
//     elemObj = $(elemObj[1]);
//
//     var newElemValue = gl.functions.refineObjectValuesData(elemObj.val());
//     elemObj.val(newElemValue);
//
//     //elemValues.push({elemName: newElemValue}));
//
//     console.log('NNNNNNNNNNNN: ' + elemName);
//
//     //TODO: мегакостыль. Убрать неопределенность при выборе элемента elemObj
//     var elemValues = gl.container.localStorage.getItem('order_addresses');
//     var ny = {};
//     ny[elemName] = newElemValue;
//     elemValues.push(ny);
//     gl.container.localStorage.setItem('order_addresses', elemValues);
//
//     return newElemValue;
// };
gl.functions.setAndGetRefinedDataToElement = function (parentElemSelector, className, elemName) {
    //TODO: parentElemSelector - рассмотреть добавление в кеш
    var elemObj = $(parentElemSelector).find('input[name="' + className + '[' + elemName + ']"]');
    elemObj = $(elemObj[1]);

    var newElemValue = gl.functions.refineObjectValuesData(elemObj.val());
    elemObj.val(newElemValue);

    return newElemValue;
};

