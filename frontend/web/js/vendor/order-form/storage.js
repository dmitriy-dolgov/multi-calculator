//!!!! TODO: проверка на типизацию (удалить скорее всего)
//TODO: наверное эту фукнцияю надо удалить.
gl.getObject('funcContainer').storageArray_removed = function (storageName) {

    // может сохраниться со старой версии
    //gl.assert((gl.container.localStorage.getItem(storageName) !== null), '"' + storageName + '" storage name already exists.');

    var lst = gl.container.localStorage;

    function init() {
        lst.setItem(storageName, []);
    }

    var storageContent = lst.getItem(storageName);
    if (storageContent === null) {
        init();
    } else {
        if (!Array.isArray(storageContent)) {
            var storageContentString = JSON.stringify(storageContent);
            gl.assert(true, `Broken element. Changed to []. storageName: "${storageName}"; storageContent: "${storageContentString}".`);
            init();
        }
    }

    this.getStorageArray = function () {
        return lst.getItem(storageName);
    };

    this.getStorageName = function () {
        return storageName;
    };

    this.clearAll = function () {
        init();
    };

    this.removeAll = function () {
        lst.removeItem(storageName);
    };

    this.addElem = function (elemData) {
        var elems = this.getStorageArray();
        elems.push(elemData);
        lst.setItem(storageName, elems);
    };

    this.removeElem = function (elemIndex) {
        var elems = this.getStorageArray();
        elems.splice(elemIndex, 1);
        lst.setItem(storageName, elems);
    };

    this.setAddresses = function () {

        var orderAddresses = gl.container.localStorage.getItem(storageName);
        if (!Array.isArray(orderAddresses)) {
            orderAddresses = [];
            gl.container.localStorage.setItem(storageName, orderAddresses);
        }

        var html = '<button onclick="">Удалить всё</button><br>';
        for (var i in orderAddresses) {
            gl.log("I:" + i);
            html += 'Адрес №' + i + '<button>Удалить</button><br>';
            for (var j in orderAddresses[i]) {
                gl.log("J:" + j);
                html += j + ': ' + orderAddresses[i][j] + '<br>';
            }
            html += '<hr>';
        }

        $('.you-panel-elements-list .content').html(html ? html : 'Нет адресов.');
    };

    this.handleAddress = function (parentElemSelectorStr, classNameStr, elemNameStrArr) {
        var orderAddresses = gl.container.localStorage.getItem(storageName);
        if (!Array.isArray(orderAddresses)) {
            orderAddresses = [];
            gl.container.localStorage.setItem(storageName, orderAddresses);
        }

        var newAddressInfo = {};
        for (var i in elemNameStrArr) {
            var elemValue = gl.functions.setAndGetRefinedDataToElement(parentElemSelectorStr, classNameStr, elemNameStrArr[i]);
            newAddressInfo[elemNameStrArr[i]] = elemValue;
        }

        orderAddresses.push(newAddressInfo);
        gl.container.localStorage.setItem(storageName, orderAddresses);

        //newElemValue
        /*var newAddressElem = {};
        ny[elemName] = newElemValue;
        elemValues.push(ny);
        gl.container.localStorage.setItem(storageName, elemValues);

        //TODO: мегакостыль. Убрать неопределенность при выборе элемента elemObj
        var elemValues = gl.container.localStorage.getItem(storageName);
        var ny = {};
        ny[elemName] = newElemValue;
        elemValues.push(ny);
        gl.container.localStorage.setItem(storageName, elemValues);*/

        // var actualAddressChanged = false;
        //
        // for (var elemName in orderAddresses) {
        //     var storedElemValue = orderAddresses[elemName];
        //     if (typeof storedElemValue == 'string') {
        //         for (var i in addressDataStrArr) {
        //             var actualElemValue = gl.functions.setAndGetRefinedDataToElement(addressDataStrArr[i]);
        //
        //             if (storedElemValue != actualElemValue) {
        //
        //             }
        //         }
        //
        //     }
        //     /*var address = orderAddresses[i];
        //     if (address.deliver_city_id != addressData.deliver_city_id) {
        //
        //     }*/
        // }
    };
};

//gl.functions.storage.setAddresses();


//gl.funcContainer.storage = {};


/**
 * Для хранилища объектов целиком (в браузере).
 *
 * @returns {{removeItem: removeItem, clear: clear, getItem: getItem, setItem: (function(*=, *=): null)}}
 */
gl.getObject('container').localStorageObj = function () {
    var storage;
    try {
        var mainStorage = window['localStorage'];
        var x = '__storage_test__';
        mainStorage.setItem(x, x);
        mainStorage.removeItem(x);

        storage = {
            //TODO: параметр length как здесь работает?
            setItem: function (key, data) {
                //TODO: обработка ошибок JSON
                mainStorage.setItem(key, JSON.stringify(data));
                //TODO: нормально сделать, this возвращать
                return null;
            },
            getItem: function (key) {
                var storedData = mainStorage.getItem(key);
                if (storedData === null) {
                    return null;
                }

                //TODO: обработка ошибок JSON
                return JSON.parse(storedData);
            },
            removeItem: function (key) {
                //TODO: проверить на возвращаемое значение
                // добавить return?
                mainStorage.removeItem(key);
            },
            //TODO: сделать метод статическим, типа того
            clear: function () {
                //TODO: проверить на возвращаемое значение
                mainStorage.clear();
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

/**
 * Объект для хранения массивов.
 * TODO: реализовать невозможным хранилище с одним name
 *
 * @param storageName
 * @returns {{addItem: (function(*=): void), getAllItems: (function(): any), removeAllItems: removeAllItems}}
 */
gl.getObject('container').localStorageArray = function (storageName) {
    var localStorage = new gl.container.localStorageObj();

    //var mainArray = localStorage.getItem(storageName);

    //TODO: а также изучить при каких условиях Array может превратиться в Object и что с этим делать.
    /*
        Закомментил исходя из соображений что этот код уже дублируется в getItems() и в любом случае будет (должен!!!)
        вызван до операций размещения.
    if (!Array.isArray(mainArray)) {
        gl.log(['Wrong mainArray! Set to default [].', mainArray]);

        mainArray = [];
        localStorage.setItem(storageName, mainArray);
    }*/

    return {
        addItem: function (data) {
            var itemData = this.getAllItems(storageName);
            itemData.push(data);

            localStorage.setItem(storageName, itemData);
        },
        getAllItems: function () {
            var storedData = localStorage.getItem(storageName);

            if (!Array.isArray(storedData)) {
                if (storedData) {
                    gl.log(['Wrong storedData (in getItems())! Set to default [].', storedData]);
                }

                storedData = [];
                localStorage.setItem(storageName, storedData);
            }

            return storedData;
        },
        removeAllItems: function () {
            //TODO: проверить на возвращаемое значение
            localStorage.removeItem(storageName);
        },
        //TODO: сделать метод статическим, типа того. Пока от греха закомментим.
        /*clear: function () {
            //TODO: проверить на возвращаемое значение
            localStorage.clear();
        }*/
    };
};
