//TODO: проверка на типизацию
gl.getObject('funcContainer').storageArray = function (storageName) {

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


gl.funcContainer.storage = {};
