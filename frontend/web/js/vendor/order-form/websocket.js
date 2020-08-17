gl.functions.websocket = {};

gl.functions.websocket.init = function () {
    var url = gl.data['yii-params']['websocket']['schema'] + '://'
        + gl.data['yii-params']['websocket']['host'] + ':' + gl.data['yii-params']['websocket']['port'];

    gl.functions.websocket.socket = new WebSocket(url);

    gl.functions.websocket.socket.onopen = function () {
        gl.functions.websocket.socketOnopen();
    };
};

gl.functions.websocket.init();

gl.functions.websocket.socketOnopen = function () {
};

gl.functions.websocket.socketOnclose = function () {
};

gl.functions.websocket.socketOnerror = function () {
};

gl.functions.websocket.socketOnmessage = function () {
};
