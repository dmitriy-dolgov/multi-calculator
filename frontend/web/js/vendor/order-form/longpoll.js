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

    alert('courier: ' + orderUid);
    return;
    gl.log('gl.functions.longpoll.waitForCourierToGo() START');

    var longPollId = 'courierOrderAccept_' + orderUid;
    var timestamp = Date.now() / 1000 | 0;
    var config = {
        url: '/shop-order/wait-courier',
        params: {t: timestamp, orderUid: orderUid},
        callback: function (data) {
            if (data) {
                if (data.order_status == 'accepted-by-courier') {
                    if (gl.functions.setUpPaneOnOrderAcceptedByCourier(data.orderUid, data.courierData)) {
                        gl.functions.longpoll.destroyLongPollProcess(longPollId);
                        //gl.functions.longpoll.waitForCourierToGo();
                    } else {
                        //TODO: обработка ошибок
                    }
                } else {
                    //TODO: обработка ошибок
                }
            }
            gl.log('INSIDE callback of waitForCourierToGo()');
        }
    };

    $.longpoll.register(longPollId, config).start();
};

//gl.functions.longpoll.waitForMerchantOrderAccept({orderUid:randomInt(0, 9999999)});

