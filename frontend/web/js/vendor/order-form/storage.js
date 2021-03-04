gl.functionss = {};
gl.functionss.storage = {
    'setAddresses': function () {

        console.log(11);

        var orderAddresses = gl.container.localStorage.getItem('order_addresses');
        if (!Array.isArray(orderAddresses)) {
            orderAddresses = [];
            gl.container.localStorage.setItem('order_addresses', orderAddresses);
        }

        console.log(22);

        var html = '';
        for (var i in orderAddresses) {
            console.log("90: +");
            for (var j in orderAddresses[i]) {
                console.log("91: ++");
                html += orderAddresses[i][j] + '<br>';
                console.log('html: ' + html);
            }
            html += '<hr>';
        }

        console.log(23);

        $('.you-panel-elements-list .content').html(html ? html : 'Нет адресов 1.');
    },
    'handleAddress': function (parentElemSelectorStr, classNameStr, elemNameStrArr) {
        var orderAddresses = gl.container.localStorage.getItem('order_addresses');
        if (!Array.isArray(orderAddresses)) {
            orderAddresses = [];
            gl.container.localStorage.setItem('order_addresses', orderAddresses);
        }

        var newAddressInfo = {};
        for (var i in elemNameStrArr) {
            var elemValue = gl.functions.setAndGetRefinedDataToElement(parentElemSelectorStr, classNameStr, elemNameStrArr[i]);
            newAddressInfo[elemNameStrArr[i]] = elemValue;
        }

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
    }
};

gl.functionss.storage.setAddresses();