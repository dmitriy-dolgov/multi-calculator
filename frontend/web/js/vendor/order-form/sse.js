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

gl.functions.sse.init = function () {
    if (!gl.functions.sse.handle) {
        gl.functions.sse.handle = new EventSource('/shop-order/wait-order-command');

        gl.functions.sse.handle.addEventListener('error', function (event) {
            //TODO: to do
            // Сообщаем о проблеме с подключением
        }, false);
    }
};

gl.functions.sse.startListen_MerchantOrderAccept = function () {
    gl.functions.sse.init();

    gl.functions.sse.handle.addEventListener('merchant-order-accept', function (event) {
        gl.log('event.data:' + event.data);
    }, false);
};

gl.functions.sse.waitForMerchantOrderAccept = function (orderUid) {

    $.post('/shop-order/order-accept', {type: 'merchant', orderUid: orderUid}, function (data) {
        
    });

};

