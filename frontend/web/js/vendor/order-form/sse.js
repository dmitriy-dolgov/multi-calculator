/**
 * SSE - Server Side Events
 */

if (gl.functions.sse) {
    alert('`gl.functions.sse` already exists!');
}

gl.functions.sse = {};

gl.functions.sse.waitForMerchantOrderAccept = function (orderUid) {

    var sse = new EventSource('/shop-order/wait-order-confirmation');
    sse.addEventListener('message', function (event) {
        /*var data = JSON.parse(event.data);
        // Отрисовываем пришедшее с сервера сообщение
        renderMessage(data);*/

        gl.log('event.data:');
        gl.log(event.data);

    }, false);

    sse.addEventListener('error', function(event) {
        //TODO: to do
        // Сообщаем о проблеме с подключением
        /*renderMessage({
            isbot: true,
            message: 'connection error',
            name: '@Chat'
        });*/
    }, false);
};

