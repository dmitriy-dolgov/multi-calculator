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

/*gl.functions.sse.init = function () {
    if (!gl.functions.sse.handle) {
        const gl.functions.sse.handle = new EventSource('/shop-order/wait-order-command');

        // gl.functions.sse.handle.onopen = function () {
        //     //gl.functions.sse.handleOpen = true;
        // };

        gl.functions.sse.handle.onerror = function () {
            switch (es.readyState) {
                case EventSource.CONNECTING:
                    break;
                case EventSource.CLOSED:
                    break;
            }
        };
    }
};*/

gl.functions.sse.startListen_OrdersAcceptance = function () {
    //gl.functions.sse.init();

    const es = new EventSource('/shop-order/wait-order-command');

    /*gl.functions.sse.handle.onopen = function () {
        //gl.functions.sse.handleOpen = true;
    };*/

    es.onerror = function () {
        switch (es.readyState) {
            case EventSource.CONNECTING:
                break;
            case EventSource.CLOSED:
                break;
        }
    };

    es.addEventListener('merchant-order-accept', function (event) {
        gl.log('event.data:');
        gl.log(event.data);

        var data = JSON.parse(event.data);

        if (data.order_status == 'accepted-by-merchant') {
            gl.log("data.order_status == 'accepted-by-merchant'");
            if (gl.functions.setUpPaneOnOrderAccepted(data.orderUid, data.merchantData)) {
                gl.log("true");
                //gl.functions.sse.handle.removeEventListener('merchant-order-accept', $.noop, false);
                //gl.functions.longpoll.waitForCourierToGo(data.orderUid);
            } else {
                //TODO: обработка ошибок
                gl.log("false");
            }
        } else if (data.order_status == 'accepted-by-courier') {
            if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderUid, data.merchantData, data.courierData)) {
                gl.functions.sse.handle.removeEventListener('merchant-order-accept', $.noop, false);
            } else {
                //TODO: обработка ошибок
            }
        } else {
            //TODO: обработка ошибок
        }

    });
};

gl.functions.sse.startOrderAccept = function (orderUid) {

    /*$.post('/shop-order/start-order-accept', {orderUid: orderUid}, function (data) {
        if (data.status != 'success') {
            alert('Unknown ajax error!');
        }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
    });*/

    setTimeout(function () {
        fetch('/shop-order/start-order-accept?orderUid=' + encodeURIComponent(orderUid));
    }, 5000);

};

/*gl.functions.sse.waitForMerchantOrderAccept = function (orderUid) {

    $.post('/shop-order/order-accept', {type: 'merchant', orderUid: orderUid}, function (data) {

    });

};*/
