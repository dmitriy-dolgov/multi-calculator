gl.functions.longpoll = {};

gl.functions.longpoll.destroyLongPollProcess = function(longPollId) {
    setTimeout(function () {
        $.longpoll.destroy(longPollId);
        gl.log('LP destroyed: ' + longPollId);
    }, 0);
};

/*gl.functions.longpoll.waitForMerchantOrderAccept = function (orderId) {

    gl.log('gl.functions.longpoll.waitForMerchantOrderAccept() START');

    var longPollId = 'merchantOrderAccept_' + orderId;
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        url: '/make-order/wait-order',
        //type: 'post', // bug in longpoll
        params: {t: timestamp, orderId: orderId},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-merchant') {

                    //alert(data.orderId);

                    if (gl.functions.setUpPaneOnOrderAccepted(data.orderId, data.merchantData)) {
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
};*/
