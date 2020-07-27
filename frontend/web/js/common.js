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

//localStorage.removeItem('orderFormState');

gl.orderFormHistory = {
    //components: [],
    /*addComponent: function (data, append) {
        gl.history.components.push({data:data, append:append});
    }*/
    ifSomethingInStore:  function () {
        var orderFormStateJson = localStorage.getItem('orderFormState');
        if (!orderFormStateJson) {
            return false;
        }

        var orderFormState = JSON.parse(orderFormStateJson);
        if (!orderFormState) {
            //TODO: handle such error (wrong JSON)
            localStorage.removeItem('orderFormState');
            return false;
        }

        return orderFormState.length;
    },
    cleanStore:  function () {
        localStorage.removeItem('orderFormState');
    },
    restoreFromStore:  function () {
        var orderFormStateJson = localStorage.getItem('orderFormState');
        if (!orderFormStateJson) {
            return;
        }

        var orderFormState = JSON.parse(orderFormStateJson);
        if (!orderFormState) {
            //TODO: handle such error (wrong JSON)
            localStorage.removeItem('orderFormState');
            return;
        }

        for (var id in orderFormState) {
            gl.functions.addComponentByData(orderFormState[id]['data'], orderFormState[id]['append'], true);
        }
    },
    addComponentByData: function (data, append) {
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


        /*this.components.push({
            'data': data,
            'append': append
        });

        gl.log('this.components.length: ' + this.components.length);*/

        // Cookies is too small and get web traffic
        // Cookies.set('orderFormState', this.components);

        var orderFormStateJson = localStorage.getItem('orderFormState');
        if (!orderFormStateJson) {
            localStorage.setItem('orderFormState', JSON.stringify([]));
            orderFormStateJson = localStorage.getItem('orderFormState');
        }

        var orderFormState = JSON.parse(orderFormStateJson);
        if (!orderFormState) {
            //TODO: handle such error (wrong JSON)
            localStorage.removeItem('orderFormState');
            this.addComponent(data, append);
            return;
        }

        //gl.log('orderFormState: ' + orderFormState);

        orderFormState.push({
            'data': data,
            'append': append
        });

        localStorage.setItem('orderFormState', JSON.stringify(orderFormState));

        gl.log('ADD: localStorage.orderFormState: ' + JSON.parse(localStorage.orderFormState).length);
    },
    removeComponentById: function (componentId, completely) {
        completely = typeof completely !== 'undefined' ? completely : false;

        gl.log('componentId: ' + componentId);

        var orderFormStateJson = localStorage.getItem('orderFormState');
        if (!orderFormStateJson) {
            return;
        }

        var orderFormState = JSON.parse(orderFormStateJson);
        if (!orderFormState) {
            //TODO: handle such error (wrong JSON)
            localStorage.removeItem('orderFormState');
            return;
        }

        for (var id in orderFormState) {
            gl.log("orderFormState[id]: ");
            gl.log(orderFormState[id]);
            gl.log("orderFormState[id]['data-id']: " + orderFormState[id]['data']['data-id']);
            if (orderFormState[id]['data']['data-id'] == componentId) {
                orderFormState.splice(id, 1);
                localStorage.setItem('orderFormState', JSON.stringify(orderFormState));
                if (!completely) {
                    break;
                }
            }
        }

        gl.log('REM: localStorage.orderFormState: ' + JSON.parse(localStorage.orderFormState).length);
    }
};
