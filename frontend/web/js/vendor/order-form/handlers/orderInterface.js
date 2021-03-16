/**
 * Для элементов пользовательского интерфейса.
 *
 * TODO: создать что-ли особый функционал чтобы проверять соответствие элементов их типам
 * (напр. orderAddresses) должен быть адресом.
 */
gl.getObject('functions.handlers').userInterface = new (function () {

    var storageName = 'user_interface';

    var storageObject = new gl.container.localStorageArray(storageName);


    //gl.funcContainer.storage.orderAddresses = new gl.funcContainer.storageArray('order_addresses');

    this.setUp = function() {
        //alert('this.setUp()');

        var userInterfaceData = storageObject.getAllItems();
        if (userInterfaceData) {
            for (var uiName in userInterfaceData) {
                var elemObj = $(uiName);
                var uiData = userInterfaceData[uiName];
                if (elemObj.length) {
                    if (uiData.add) {
                        elemObj.addClass(uiData.className);
                    } else {
                        elemObj.removeClass(uiData.className);
                    }

                }
            }
        }
    };
})();

gl.functions.handlers.userInterface.setUp();
