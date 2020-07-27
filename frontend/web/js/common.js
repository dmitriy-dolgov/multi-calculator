'use strict';
/*(function () {
    'use strict';
}());*/

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
        result += ' <span class="c-sign-in-price">' + gl.escapeHtml(currency) + '</span>';
    }
    return result;
};

//TODO: pz_comp ?
gl.functions.unwrapBottom = function (elem) {
    $(elem).next().toggle();
};

gl.orderFormHistory = {
    components: [],
    /*addComponent: function (data, append) {
        gl.history.components.push({data:data, append:append});
    }*/
    saveState: function (data, append) {
        /*var orderFormState = [];
        $('.component-holder .added-component').each(function () {
            var component = $(this);
            var dataContainer = component.find('.data-container');
            var data = {};
            data['data-id'] = component.data('id');data-container
            data['data-amount'] = component.data('amount');
            data['data-name'] = dataContainer.data('name');
            data['data-short_name'] = dataContainer.data('short_name');
            data['data-image'] = dataContainer.data('image');
            data['data-video'] = dataContainer.data('video');
            data['data-price'] = dataContainer.data('price');
            data['data-price_discount'] = dataContainer.data('price_discount');
            data['data-item_select_min'] = dataContainer.data('item_select_min');
            data['data-item_select_max'] = dataContainer.data('item_select_max');
            data['data-unit_name'] = dataContainer.data('unit_name');
            data['data-unit_value'] = dataContainer.data('unit_value');
            data['data-unit_value_min'] = dataContainer.data('unit_value_min');
            data['data-unit_value_max'] = dataContainer.data('unit_value_max');
            data['data-unit_switch_group_id'] = dataContainer.data('unit_switch_group_id');
            data['data-unit_switch_group_name'] = dataContainer.data('unit_switch_group_name');

            orderFormState.push(data);
        });*/

        this.components.push({
            'data': data,
            'append': append
        });

        Cookies.set('orderFormState', this.components);
    }
};
