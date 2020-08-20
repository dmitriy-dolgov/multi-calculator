gl.functions.longpoll = {};

gl.functions.longpoll.waitForMerchantOrderAccept = function (orderUid) {
    
    $.post('/shop-order/wait-order', {orderUid: orderUid}, function (data) {
        if (data.status == 'success') {
            if (data.data.order_status == 'accepted-by-merchant') {
                if (gl.functions.setUpPaneOnOrderAccepted(data.data.orderUid, data.data.merchantData)) {
                    gl.functions.longpoll.waitForCourierToGo(orderUid);
                } else {
                    //TODO: обработка ошибок
                }
            } else {
                //TODO: обработка ошибок
            }
        } else {
            alert('Unknown ajax error!');
        }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
    });
};

gl.functions.longpoll.waitForCourierToGo = function (orderUid) {

    gl.log('gl.functions.longpoll.waitForCourierToGo() START, orderUid: ' + orderUid);

    $.post('/shop-order/wait-courier', {orderUid: orderUid}, function (data) {
        if (data.status == 'success') {
            if (data.data.order_status == 'accepted-by-courier') {
                if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.data.orderUid, data.data.merchantData, data.data.courierData)) {
                    gl.functions.longpoll.waitForCourierToGo(orderUid);
                } else {
                    //TODO: обработка ошибок
                }
            } else {
                //TODO: обработка ошибок
            }
        } else {
            alert('Unknown ajax error!');
        }
    }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
    });
};
