//TODO: сделать всю инициализацию цепочки через gl.getObject()
//var hObj = gl.getObject('functions.handlers');
gl.log(["hObj 1: ", gl]);
//gl.functions.handlers.orderAddresses = new (function () {
gl.getObject('functions.handlers').orderAddresses = new (function () {

    //var storageName = 'order_addresses';
    //gl.funcContainer.storage.orderAddresses = new gl.funcContainer.storageArray('order_addresses');

    var storage = gl.container.localStorageArray('order_addresses');

    this.setAddresses = function() {
        //alert('this.setAddresses');

        /*var orderAddresses = gl.container.localStorage.getItem(storageName);
        if (!Array.isArray(orderAddresses)) {
            orderAddresses = [];
            gl.container.localStorage.setItem(storageName, orderAddresses);
        }*/

        var orderAddresses = storage.getAllItems();

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

    /*this.setAddresses = function() {
        //alert('this.setAddresses');

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
    };*/

    this.handleAddress = function (parentElemSelectorStr, classNameStr, elemNameStrArr) {
        /*var orderAddresses = gl.container.localStorage.getItem(storageName);
        if (!Array.isArray(orderAddresses)) {
            orderAddresses = [];
            gl.container.localStorage.setItem(storageName, orderAddresses);
        }*/

        var newAddressInfo = {};
        for (var i in elemNameStrArr) {
            var elemValue = gl.functions.setAndGetRefinedDataToElement(parentElemSelectorStr, classNameStr, elemNameStrArr[i]);
            newAddressInfo[elemNameStrArr[i]] = elemValue;
        }

        storage.addItem(newAddressInfo);

        //orderAddresses.push(newAddressInfo);
        //gl.container.localStorage.setItem(storageName, orderAddresses);

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
})();

gl.functions.handlers.orderAddressesTT = 234;

gl.functions.handlers.test = {};

gl.log(["hObj 2: ", gl]);



//gl.functions.handlers.orderAddresses.setAddresses();


