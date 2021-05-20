/**
 * !!!! TODO: проверка на типизацию (удалить скорее всего)
 */


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
                //mainStorage.clear();
            },
            serialize: function () {
                //debugger;
                return mainStorage['orderFormState'];
                //return JSON.stringify(mainStorage['orderFormState']);
                //orderFormState();
                //var $sr = JSON.stringify(mainStorage['orderFormState']);
                //return $sr;
                //return JSON.stringify(mainStorage['interface.actual']);
                //return JSON.stringify(mainStorage);
                //debugger;
                //var sdf = JSON.stringify(mainStorage);
                //return sdf;-
                //return mainStorage;
            },
            unserialize: function (data) {
                //debugger;
                mainStorage.setItem('orderFormState', data);
                //mainStorage['orderFormState'] = data;
                //return JSON.stringify(mainStorage);
                //var dataArr = JSON.parse(data);
                //mainStorage.setItem('interface.actual', dataArr);
                //var dataArr = JSON.parse(data);
                //mainStorage['orderFormState'] = dataArr;
                //var sr = JSON.stringify(mainStorage['orderFormState']);
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
            },
            serialize: function () {
                gl.log(['Storage do not work: serialize()', name]);
                //TODO: нормально сделать, this возвращать
                return null;
            },
            unserialize: function (data) {
                gl.log(['Storage do not work: unserialize()', name]);
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

//alert(L);

