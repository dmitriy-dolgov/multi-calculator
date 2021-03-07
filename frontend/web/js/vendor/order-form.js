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
});

$('.menu-item.pizzas').click(function () {
    $('.unwrapped-panel').removeClass('unwrap');
    $('.pizzas-panel-elements-list').toggleClass('unwrap');
});

$('.menu-item.orders').click(function () {
    $('.unwrapped-panel').removeClass('unwrap');
    $('.orders-panel-elements-list').toggleClass('unwrap');
});

$('.menu-item.you').click(function () {
    $('.unwrapped-panel').removeClass('unwrap');
    $('.you-panel-elements-list').toggleClass('unwrap');
});

/**
 * Объект для хранилища строк.
 *
 * @returns {{removeItem: removeItem, clear: clear, getItem: getItem, setItem: (function(*=, *=): null)}}
 */
gl.functions.getLocalStorage = function () {
    var storage;
    try {
        var testStorage = window['localStorage'];
        var x = '__storage_test__';
        testStorage.setItem(x, x);
        testStorage.removeItem(x);

        storage = {
            //TODO: параметр length как здесь работает?
            setItem: function (key, data) {
                //TODO: обработка ошибок JSON
                testStorage.setItem(key, JSON.stringify(data));
                //TODO: нормально сделать, this возвращать
                return null;
            },
            getItem: function (key) {
                if (testStorage.getItem(key) === null) {
                    return null;
                }

                //TODO: обработка ошибок JSON
                return JSON.parse(testStorage.getItem(key));
            },
            removeItem: function (key) {
                //TODO: проверить на возвращаемое значение
                testStorage.removeItem(key);
            },
            //TODO: сделать метод статическим, типа того
            clear: function () {
                //TODO: проверить на возвращаемое значение
                testStorage.clear();
            }
        };

    } catch (e) {
        storage = {
            //TODO: параметр length как здесь работает?
            setItem: function (elem) {
                gl.log(['Storage do not work: setItem()', elem]);
                //TODO: нормально сделать, this возвращать
                return null;
            },
            getItem: function (name) {
                gl.log(['Storage do not work: getItem()', name]);
                return null;
            },
            removeItem: function (name) {
                gl.log(['Storage do not work: removeItem()', name]);
                //TODO: нормально сделать, this возвращать
                return null;
            },
            clear: function () {
                gl.log(['Storage do not work: clear()', name]);
                //TODO: нормально сделать, this возвращать
                return null;
            }
        };
        //TODO: рассмотреть закомментированый код ниже (https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API):
        /*var hasStorage = e instanceof DOMException && (
                // everything except Firefox
            e.code === 22 ||
            // Firefox
            e.code === 1014 ||
            // test name field too, because code might not be present
            // everything except Firefox
            e.name === 'QuotaExceededError' ||
            // Firefox
            e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
            // acknowledge QuotaExceededError only if there's something already stored
            (storage && storage.length !== 0);*/
    }

    return storage;
};

if (!gl.container) {
    gl.container = {};
}

if (!gl.fContainer) {
    gl.fContainer = {};
}

gl.container.localStorage = new gl.functions.getLocalStorage();

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

