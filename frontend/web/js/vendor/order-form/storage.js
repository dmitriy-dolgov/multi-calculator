//TODO: проверка на типизацию
gl.getObject('funcContainer').storageArray = function (storageName) {

    // может сохраниться со старой версии
    //gl.assert((gl.container.localStorage.getItem(storageName) !== null), '"' + storageName + '" storage name already exists.');

    var lst = gl.container.localStorage;

    var storageContent = lst.getItem(storageName);
    if (storageContent === null) {
        lst.setItem(storageContent = []);
    }

    this.getStorageName = function () {
        return storageName;
    };

    this.removeItem = function () {
        lst.removeItem(storageName);
    };

    this.createItem = function () {

    };

    //TODO: проработать удаление, чтобы не на каком-то объекте происходило (статическая функция)
    /*this.clear = function () {
        gl.assert('Simple Storage clear!!!');
        gl.container.localStorage.clear();
    };*/



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


gl.getObject('functions').storage = {
    'orderAddresses': new gl.funcContainer.storageArray('order_addresses')
};

//console.log('setAddresses 1');
//gl.functions.storage.orderAddresses.setAddresses();
console.log('setAddresses 2');
//gl.functions.storage.orderAddresses.removeStorage();
console.log('setAddresses 3');
//console.log(gl.functions.storage.orderAddresses.storageName);
console.log('setAddresses 4444');