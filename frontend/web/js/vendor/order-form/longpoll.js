gl.functions.longpoll = {};

function randomInt(min, max) {
    return min + Math.floor((max - min) * Math.random());
}

gl.functions.longpoll.waitForMerchantOrderAccept = function (data) {
    var id = 'merchantOrderAccept_' + data.orderId;
    //var id = 'merchantOrderAccept';
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        //url: '/shop-order/wait-order?order_id=' + encodeURIComponent(data.orderId),
        url: '/shop-order/wait-order',
        params: {t: timestamp},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-merchant') {

                    //alert(data.orderId);

                    if (gl.functions.setUpPaneOnOrderAccepted(data.orderId, data.merchantData)) {
                        $.longpoll.destroy(id);
                        gl.functions.longpoll.waitForCourierToGo();
                    } else {
                        //TODO: обработка ошибок
                    }
                } else {
                    //TODO: обработка ошибок
                }
            }
            //Query.longpoll.get('myId').stop();
            //jQuery.longpoll.destroy('myId');
        }
    };
    $.longpoll.register(id, config).start();
};

gl.functions.longpoll.waitForCourierToGo = function () {
    var id = 'courierOrderAccept';
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        url: '/shop-order/wait-courier',
        params: {t: timestamp},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-courier') {

                    if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderId, data.courierData)) {
                        $.longpoll.destroy(id);
                        //gl.functions.longpoll.waitForCourierToGo();
                    } else {
                        //TODO: обработка ошибок
                    }
                } else {
                    //TODO: обработка ошибок
                }
            }
        }
    };
    $.longpoll.register(id, config).start();
};

//gl.functions.longpoll.waitForMerchantOrderAccept({orderId:randomInt(0, 9999999)});

