/**
 * SSE - Server Side Events
 */

if (gl.functions.sse) {
    alert('`gl.functions.sse` already exists!');
}

gl.functions.sse = {};

/**
 * @type {EventSource|null}
 */
gl.functions.sse.handle = null;

//gl.functions.sse.handleOpen = false;

gl.functions.sse.init = function () {
    if (!gl.functions.sse.handle) {
        gl.functions.sse.handle = new EventSource('/shop-order/wait-order-command');

        gl.functions.sse.handle.onopen = function () {
            //gl.functions.sse.handleOpen = true;
        };

        gl.functions.sse.handle.onerror = function () {
            //gl.functions.sse.handleOpen = false;
            //TODO: to do
            // Сообщаем о проблеме с подключением
        };
    }
};

gl.functions.sse.startListen_OrdersAcceptance = function () {
    gl.functions.sse.init();

    gl.functions.sse.handle.addEventListener('merchant-order-accept', function (event) {
        gl.log('event.data:');
        gl.log(event.data);

        if (event.data.order_status == 'accepted-by-merchant') {
            if (gl.functions.setUpPaneOnOrderAccepted(event.data.orderUid, event.data.merchantData)) {
                //gl.functions.sse.handle.removeEventListener('merchant-order-accept', $.noop, false);
                //gl.functions.longpoll.waitForCourierToGo(event.data.orderUid);
            } else {
                //TODO: обработка ошибок
            }
        } else if (event.data.order_status == 'accepted-by-courier') {
            if (gl.functions.setUpPaneOnOrderAcceptedByCourier(event.data.orderUid, event.data.merchantData, event.data.courierData)) {
                gl.functions.sse.handle.removeEventListener('merchant-order-accept', $.noop, false);
            } else {
                //TODO: обработка ошибок
            }
        } else {
            //TODO: обработка ошибок
        }

    }, false);
};

gl.functions.sse.startOrderAccept = function (orderUid) {

    $.post('/shop-order/start-order-accept', {orderUid: orderUid}, function (data) {
        if (data.status != 'success') {
            alert('Unknown ajax error!');
        }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
    });

};

/*gl.functions.sse.waitForMerchantOrderAccept = function (orderUid) {

    $.post('/shop-order/order-accept', {type: 'merchant', orderUid: orderUid}, function (data) {

    });

};*/
