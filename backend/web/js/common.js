'use strict';

if (!window.gl) {
    var gl = {};
}

// For global functions
if (!gl.functions) {
    gl.functions = {};
}

// For global data
if (!gl.data) {
    gl.data = {};
}

gl.escapeHtml = function (text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    var result = '';
    try {
        result = text.toString();
    } catch (e) {

    }

    return result.replace(/[&<>"']/g, function (m) {
        return map[m];
    });
};

gl.log = function (msg) {
    if (window.console) {
        if (typeof msg == 'string') {
            console.log(msg);
        } else {    // must be an array
            for (var id in msg) {
                console.log(msg[id]);
            }
        }
    }
};

gl.inIframe = function () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
};

gl.get = function(obj, key) {
    return key.split('.').reduce(function(o, x) {
        return (typeof o == 'undefined' || o === null) ? o : o[x];
    }, obj);
};

gl.has = function(obj, key) {
    return key.split('.').every(function(x) {
        if(typeof obj != 'object' || obj === null || ! x in obj)
            return false;
        obj = obj[x];
        return true;
    });
};

gl.createMapMarkerPopupHtml = function (data) {
    var html = '';
    if (data.name) {
        html += '<strong>' + this.escapeHtml(data.name) + '</strong>';
    }
    if (data.address) {
        html += (html ? '<br>' : '') + '<i style="margin-top:10px;display:inline-block;">' + this.escapeHtml(data.address) + '</i>';
    }
    if (data.url) {
        html += (html ? '<br>' : '') + '<a style="margin-top:10px;display:inline-block;" href="' + this.escapeHtml(data.url) + '" target="_blank">' + this.escapeHtml(data.url) + '</a>';
    }
    return html;
};

gl.handleJqueryAjaxFail = function(XMLHttpRequest, textStatus, errorThrown, howToHandle) {
    howToHandle = typeof howToHandle !== 'undefined' ? howToHandle : 'console';
    if (howToHandle == 'alert') {
        alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
    } else if (howToHandle == 'console') {
        this.log('Jquery Ajax Fail Info:');
        this.log('XMLHttpRequest:');
        this.log(XMLHttpRequest);
        this.log('textStatus:');
        this.log(textStatus);
        this.log('errorThrown:');
        this.log(errorThrown);
    }
};

gl.handleFailCustom = function(message, howToHandle) {
    howToHandle = typeof howToHandle !== 'undefined' ? howToHandle : 'console';
    if (howToHandle == 'alert') {
        alert(message);
    } else if (howToHandle == 'console') {
        this.log('Fail Custom Message: ' + message);
    }
};
