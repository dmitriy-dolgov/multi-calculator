gl.functions.longpoll = {};

gl.functions.longpoll.waitForMerchantOrderAccept = function () {
    var id = 'merchantOrderAccept';
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        url: '/shop-order/wait-order',
        //params: {t: 1485714246},
        params: {t: timestamp},
        callback: function (data) {
            if (data) {
                alert(data);
            }
            //Query.longpoll.get('myId').stop();
            //jQuery.longpoll.destroy('myId');
        }
    };
    jQuery.longpoll.register(id, config).start();
};

gl.functions.longpoll.waitForMerchantOrderAccept();

