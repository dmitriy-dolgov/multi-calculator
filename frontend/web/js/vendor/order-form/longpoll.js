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

                    alert(data.orderId);

                    var container = $('.orders-container [data-order-id="' + data.orderId + '"]');
                    if (container.length) {
                        var orderInfoJson = container.data('order-info');
                        var orderInfoObj = JSON.parse(orderInfoJson);
                        orderInfoObj.orderStatus = 'accepted-by-merchant';
                        container.data('order-info', JSON.stringify(orderInfoObj));

                        //TODO: здесь мигает окно "заказы"

                        $.longpoll.destroy(id);
                    } else {
                        //TODO: обработка ошибок с данными заказа
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

//gl.functions.longpoll.waitForMerchantOrderAccept({orderId:randomInt(0, 9999999)});

