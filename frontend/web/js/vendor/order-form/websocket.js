gl.functions.websocket = {};

gl.functions.websocket.init = function () {
    var url = gl.data['yii-params']['websocket']['schema'] + '://'
        + gl.data['yii-params']['websocket']['host'] + ':' + gl.data['yii-params']['websocket']['port'];

    gl.functions.websocket.socket = new WebSocket(url);
    gl.log(['gl.functions.websocket.socket:', gl.functions.websocket.socket]);

    gl.functions.websocket.socket.onopen = function () {
        gl.functions.websocket.socketOnOpen();
    };
    gl.functions.websocket.socket.onclose = function () {
        gl.functions.websocket.socketOnClose();
    };
    gl.functions.websocket.socket.onerror = function () {
        gl.functions.websocket.socketOnError();
    };
    gl.functions.websocket.socket.onmessage = function (event) {
        gl.functions.websocket.socketOnMessage(event);
    };
};

gl.functions.websocket.init();

gl.functions.websocket.socketOnOpen = function () {
    gl.log('SOCKET ON OPEN EVENT');
    var msg = {
        jsonrpc: "2.0",
        id: 1,
        method: "customer/join",
        params: {
            id: 1,
            info: {
                user: "new",
            }
        }
    };
    gl.functions.websocket.socket.send(JSON.stringify(msg));
};

gl.functions.websocket.send = function (info) {
    // 0 	CONNECTING 	Socket has been created. The connection is not yet open.
    // 1 	OPEN 	The connection is open and ready to communicate.
    // 2 	CLOSING 	The connection is in the process of closing.
    // 3 	CLOSED 	The connection is closed or couldn't be opened.

    gl.log('SOCKET ON SEND');

    if (gl.functions.websocket.socket.readyState == 1) {
        var msg = {
            jsonrpc: "2.0",
            id: 1,
            method: "customer/newOrderCreated",
            params: {
                id: 1,
                info: info
            }
        };

        gl.functions.websocket.socket.send(JSON.stringify(msg));
    } else {
        //TODO: handle error
        alert('Произошла ошибка, попробуйте пожалуйста позже!');
    }
};

gl.functions.websocket.socketOnClose = function () {
    //TODO: обрабатывать
    gl.log('SOCKET ON CLOSE EVENT');
};

gl.functions.websocket.socketOnError = function () {
    //TODO: обрабатывать
    gl.log('SOCKET ON ERROR EVENT');
};

gl.functions.websocket.socketOnMessage = function (event) {
    gl.log('SOCKET ON MESSAGE EVENT');
    gl.log(['event.data:', event.data]);
};
