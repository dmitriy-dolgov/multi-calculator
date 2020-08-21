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

    /*if (!gl.functions.sse.handleOpen) {
        var interval = setInterval(function () {
            if (gl.functions.sse.handleOpen) {
                clearInterval(interval);
            }
        }, 300);
    }*/

    if (!gl.functions.sse.listeners) {
        gl.functions.sse.listeners = {};
    }

    if (!gl.functions.sse.listeners['order-accept']) {
        gl.functions.sse.listeners['order-accept'] = gl.functions.sse.handle.addEventListener('merchant-order-accept', function (event) {
            gl.log('event.data:');
            gl.log(event.data);
        }, false);
    }
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
