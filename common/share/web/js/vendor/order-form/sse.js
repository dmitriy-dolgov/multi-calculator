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
    if (gl.functions.sse.es) {
        gl.log('gl.functions.sse.es already initialized');
        return;
    }

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

    gl.functions.sse.es.addEventListener('accepted-by-merchant', function (event) {
        gl.log("data.order_status == 'accepted-by-merchant'");
        //gl.log(['event.data:', event.data]);

        var data = JSON.parse(event.data);

        debugger;
        debugger;
        if (gl.functions.setUpPaneOnOrderAccepted(data.orderUid, data.data.merchantData)) {
            gl.log("setUpPaneOnOrderAccepted() - true");
            //gl.functions.sse.se.removeEventListener('merchant-order-accept', $.noop, false);
            //gl.functions.longpoll.waitForCourierToGo(data.orderUid);
        } else {
            //TODO: обработка ошибок
            gl.log("setUpPaneOnOrderAccepted() - false");
        }
    });

    gl.functions.sse.es.addEventListener('accepted-by-courier', function (event) {
        gl.log("data.order_status == 'accepted-by-courier'");
        //gl.log(['event.data:', event.data]);

        var data = JSON.parse(event.data);

        if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderUid, data.data.merchantData, data.data.courierData)) {
            gl.log("setUpPaneOnOrderAcceptedByCourier() - true");
            //gl.functions.sse.se.removeEventListener('merchant-order-accept', $.noop, false);
        } else {
            //TODO: обработка ошибок
            gl.log("setUpPaneOnOrderAcceptedByCourier() - false");
        }
    });

    gl.functions.sse.es.addEventListener('courier-arrived', function (event) {
        gl.log("data.order_status == 'successfully-finished'");
        //gl.log(['event.data:', event.data]);

        var data = JSON.parse(event.data);

        if (gl.functions.setUpPaneOnOrderCourierArrived(data.orderUid, data.data.merchantData, data.data.courierData)) {
            gl.log("setUpPaneOnOrderCourierArrived() - true");
            //gl.functions.sse.se.removeEventListener('merchant-order-accept', $.noop, false);
        } else {
            //TODO: обработка ошибок
            gl.log("setUpPaneOnOrderCourierArrived() - false");
        }
    });

    gl.functions.sse.es.addEventListener('successfully-finished', function (event) {
        gl.log("data.order_status == 'successfully-finished'");
        //gl.log(['event.data:', event.data]);

        var data = JSON.parse(event.data);

        if (gl.functions.setUpPaneOnOrderSuccessfullyFinished(data.orderUid, data.data.merchantData, data.data.courierData)) {
            gl.log("setUpPaneOnOrderSuccessfullyFinished() - true");
            //gl.functions.sse.se.removeEventListener('merchant-order-accept', $.noop, false);
        } else {
            //TODO: обработка ошибок
            gl.log("setUpPaneOnOrderSuccessfullyFinished() - false");
        }
    });

};

gl.functions.sse.startOrderAccept = function (orderUid) {

    gl.log('startOrderAccept: ' + orderUid);

    $.post('/make-order/start-order-accept', {orderUid: orderUid}, function (data) {
        if (data.status != 'success') {
            alert('Unknown ajax error!');
        } else {
            gl.log('Order listening successfully started.');
        }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
    });

    /*setInterval(function () {
        $.post('/make-order/dummy', {orderUid: orderUid}, function (data) {
            gl.log('DUMMY PASSED');
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
        });
    }, 7000);*/
};

gl.functions.sse.startListen_OrderStatusAcceptance();
