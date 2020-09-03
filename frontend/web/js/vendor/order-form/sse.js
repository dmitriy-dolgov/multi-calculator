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
gl.functions.sse.se = null;

gl.functions.sse.startListen_OrderStatusAcceptance = function () {
    gl.functions.sse.es = new EventSource('/make-order/wait-order-command');  // { withCredentials: true });  // http://username:password@github.com - HTTP basic auth

    gl.functions.sse.es.onopen = function () {
        gl.log('EventSource.OPENED');
    };

    gl.functions.sse.es.onerror = function () {
        switch (gl.functions.sse.es.readyState) {
            case EventSource.CONNECTING:
                gl.log('EventSource.CONNECTING');
                break;
            case EventSource.CLOSED:
                gl.log('EventSource.CLOSED');
                break;
        }
    };

    gl.functions.sse.es.addEventListener('ping', function (event) {
        var obj = JSON.parse(event.data);
        //gl.log('ping at ' + obj.time);
    });

    gl.functions.sse.es.addEventListener('order-status', function (event) {
        gl.log(['event.data:', event.data]);

        var data = JSON.parse(event.data);

        if (data.order_status == 'accepted-by-merchant') {
            gl.log("data.order_status == 'accepted-by-merchant'");
            if (gl.functions.setUpPaneOnOrderAccepted(data.orderUid, data.merchantData)) {
                gl.log("true");
                //gl.functions.sse.se.removeEventListener('merchant-order-accept', $.noop, false);
                //gl.functions.longpoll.waitForCourierToGo(data.orderUid);
            } else {
                //TODO: обработка ошибок
                gl.log("false");
            }
        } else if (data.order_status == 'accepted-by-courier') {
            if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderUid, data.merchantData, data.courierData)) {
                //gl.functions.sse.se.removeEventListener('merchant-order-accept', $.noop, false);
            } else {
                //TODO: обработка ошибок
            }
        } else {
            //TODO: обработка ошибок
        }

    });
};

gl.functions.sse.startOrderAccept = function (orderUid) {

    //gl.functions.sse.es.OPEN

    /*$.get('/make-order/start-order-accept', {orderUid: orderUid}, function (data) {
        if (data.status != 'success') {
            alert('Unknown ajax error!');
        }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
    });*/

    fetch('/make-order/start-order-accept?orderUid=' + encodeURIComponent(orderUid))
    //fetch('/index2.php?orderUid=' + encodeURIComponent(orderUid))
        .then(function (response) {
            gl.log('response:');
            gl.log(response);
            gl.log('response.json():');
            gl.log(response.json());
        });
};

/*gl.functions.sse.waitForMerchantOrderAccept = function (orderUid) {

    $.post('/make-order/order-accept', {type: 'merchant', orderUid: orderUid}, function (data) {

    });

};*/

gl.functions.sse.startListen_OrderStatusAcceptance();
