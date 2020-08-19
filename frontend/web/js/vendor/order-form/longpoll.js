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
                        /*$.longpoll.destroy('merchantOrderAccept');
                        gl.log('gl.functions.longpoll.waitForCourierToGo();');
                        gl.log('destroyed: ' + id);
                        setTimeout(function () {*/
                        gl.functions.longpoll.waitForCourierToGo();
                        //}, 5000);

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

    $.longpoll.register(id, config).start();

    /*gl.functions.longpoll.merchantInterval = setInterval(function () {
        $.get('/shop-order/accept-order-by-merchant');
    }, 5000);*/
};

gl.functions.longpoll.waitForCourierToGo = function () {

    setTimeout(function () {
        $.longpoll.destroy('merchantOrderAccept');
        gl.log('3: gl.functions.longpoll.waitForCourierToGo();');
        gl.log('3: destroyed: ' + id);
    }, 0);

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
            gl.log('INSIDE callback of waitForCourierToGo()');
        }
    };

    $.longpoll.register(id, config).start();

    /*setInterval(function () {
        $.get('/shop-order/accept-order-by-courier');
    }, 5000);*/
};

//gl.functions.longpoll.waitForMerchantOrderAccept({orderId:randomInt(0, 9999999)});

