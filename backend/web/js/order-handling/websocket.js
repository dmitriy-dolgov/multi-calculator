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
    var msg = '{' +
        '"jsonrpc":"2.0",' +
        '"id":gl.data.user_socket_id,' +
        '"method":"orderTeam/join",' +
        '"params":{' +
        '  "id":"1",' +
        '  "info":{' +
        //'  "age":"19",' +
        //'  "gender":"f"' +
        '  "user":"new"' +
        '  }' +
        '}' +
        '}';
    gl.functions.websocket.socket.send(msg);
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
