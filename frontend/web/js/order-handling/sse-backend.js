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
gl.functions.sse.es = null;

gl.functions.sse.startListen_OrdersAcceptance = function () {
    gl.functions.sse.es = new EventSource('/accept-order/wait-order-command');  // { withCredentials: true });  // http://username:password@github.com - HTTP basic auth

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

    gl.functions.sse.es.addEventListener('new-order', function (event) {
        //gl.log(['event.data: ', event.data]);

        try {
            var data = JSON.parse(event.data);
            gl.functions.newOrderReceived(data.html);
            //location.reload();
        } catch (e) {
            gl.log(['message listener error: ', e]);
        }

    });
};

gl.functions.sse.startListen_OrdersAcceptance();
