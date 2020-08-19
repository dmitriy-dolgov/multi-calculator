gl.functions.longpoll = {};

/*function randomInt(min, max) {
    return min + Math.floor((max - min) * Math.random());
}*/

gl.functions.longpoll.destroyLongPollProcess = function(longPollId) {
    setTimeout(function () {
        $.longpoll.destroy(longPollId);
        gl.log('LP destroyed: ' + longPollId);
    }, 0);
};

gl.functions.longpoll.waitForMerchantOrderAccept = function (orderUid) {

    gl.log('gl.functions.longpoll.waitForMerchantOrderAccept() START');

    var longPollId = 'merchantOrderAccept_' + orderUid;
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        url: '/shop-order/wait-order',
        //type: 'post', // bug in longpoll
        params: {t: timestamp, orderUid: orderUid},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-merchant') {

                    //alert(data.orderUid);

                    if (gl.functions.setUpPaneOnOrderAccepted(data.orderUid, data.merchantData)) {
                        gl.functions.longpoll.destroyLongPollProcess(longPollId);
                        gl.functions.longpoll.waitForCourierToGo();
                    } else {
                        //TODO: обработка ошибок
                    }
                } else {
                    //TODO: обработка ошибок
                }
            }
            gl.log('INSIDE callback of waitForMerchantOrderAccept()');
            //Query.longpoll.get('myId').stop();
            //jQuery.longpoll.destroy('myId');
        }
    };

    $.longpoll.register(longPollId, config).start();
};

gl.functions.longpoll.waitForCourierToGo = function (orderUid) {

    gl.log('gl.functions.longpoll.waitForCourierToGo() START');

    var longPollId = 'courierOrderAccept_' + orderUid;
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        url: '/shop-order/wait-courier',
        params: {t: timestamp, orderUid: orderUid},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-courier') {
                    if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderUid, data.courierData)) {
                        gl.functions.longpoll.destroyLongPollProcess(longPollId);
                        //gl.functions.longpoll.waitForCourierToGo();
                    } else {
                        //TODO: обработка ошибок
                    }
                } else {
                    //TODO: обработка ошибок
                }
            }
            gl.log('INSIDE callback of waitForCourierToGo()');
        }
    };

    $.longpoll.register(longPollId, config).start();
};

//gl.functions.longpoll.waitForMerchantOrderAccept({orderUid:randomInt(0, 9999999)});

