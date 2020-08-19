gl.functions.longpoll = {};

function randomInt(min, max) {
    return min + Math.floor((max - min) * Math.random());
}

gl.functions.longpoll.waitForMerchantOrderAccept = function (data) {

    gl.log('gl.functions.longpoll.waitForMerchantOrderAccept() START');

    //var id = 'merchantOrderAccept_' + data.orderId;
    var id = 'merchantOrderAccept';
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        //url: '/shop-order/wait-order?order_id=' + encodeURIComponent(data.orderId),
        url: '/shop-order/wait-order',
        //type: 'post', // bug in longpoll
        params: {t: timestamp, dtt: 'my_data_dtt'},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-merchant') {

                    //alert(data.orderId);

                    if (gl.functions.setUpPaneOnOrderAccepted(data.orderId, data.merchantData)) {
                        $.longpoll.destroy(id);
                        gl.log('gl.functions.longpoll.waitForCourierToGo();');
                        gl.log('destroyed: ' + id);
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

    /*config = {
        //url: '/shop-order/wait-order?order_id=' + encodeURIComponent(data.orderId),
        url: '/shop-order/wait-order',
        params: {"event-newMessage":0},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-merchant') {

                    //alert(data.orderId);

                    if (gl.functions.setUpPaneOnOrderAccepted(data.orderId, data.merchantData)) {
                        $.longpoll.destroy(id);
                        gl.log('gl.functions.longpoll.waitForCourierToGo();');
                        gl.log('destroyed: ' + id);
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
    };*/

    $.longpoll.register(id, config).start();

    /*gl.functions.longpoll.merchantInterval = setInterval(function () {
        $.get('/shop-order/accept-order-by-merchant');
    }, 5000);*/
};

gl.functions.longpoll.waitForCourierToGo = function () {

    //clearInterval(gl.functions.longpoll.merchantInterval);

    gl.log('gl.functions.longpoll.waitForCourierToGo() START');

    var id = 'courierOrderAccept';
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        url: '/shop-order/wait-courier',
        //type: 'post',
        params: {t: timestamp, md: 'my_data_md'},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-courier') {

                    if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderId, data.courierData)) {
                        $.longpoll.destroy(id);
                        gl.log('destroyed 2: ' + id);
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

    /*config = {
        url: '/shop-order/wait-courier',
        params: {"event-newMessage":0},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-courier') {

                    if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderId, data.courierData)) {
                        $.longpoll.destroy(id);
                        gl.log('destroyed 2: ' + id);
                        //gl.functions.longpoll.waitForCourierToGo();
                    } else {
                        //TODO: обработка ошибок
                    }
                } else {
                    //TODO: обработка ошибок
                }
            }
        }
    };*/

    $.longpoll.register(id, config).start();

    /*setInterval(function () {
        $.get('/shop-order/accept-order-by-courier');
    }, 5000);*/
};

//gl.functions.longpoll.waitForMerchantOrderAccept({orderId:randomInt(0, 9999999)});

