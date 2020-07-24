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
        console.log(msg);
    }
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

gl.beautifyPrice = function (price, currency) {
    var dolCentArr = price.split('.');
    var result = dolCentArr[0] + '.<sup>' + dolCentArr[1] + '</sup>';
    if (currency) {
        result += ' <span class="c-sign-in-price">' + currency + '</span>';
    }
    return result;
};