'use strict';
/*(function () {
    'use strict';
}());*/

if (!window.gl) {
    var gl = {};
}

//TODO: также реализовать gl.getArray
gl.getObject = function (name) {
    //var currName = ['gl'];
    var nameList = name.split('.');


    //gl.log(['nameList', nameList]);

    //Array.prototype.push.apply(currName, nameList);
    //gl.log(['nameList 2', nameList]);

    var lastElem;
    //var object = gl;
    nameList.reduce(function (o, s) {
        if (typeof o[s] === 'undefined') {
            o[s] = {};
        }
        return lastElem = o[s];
        //}, object);
    }, this);

    //gl.log(['lastElem: ', lastElem]);

    return lastElem;
};

// For global functions
if (!gl.functions) {
    gl.functions = {};
}

// For global data
if (!gl.data) {
    gl.data = {};
}

if (!gl.config) {
    gl.config = {};
}

if (!gl.info) {
    gl.info = {};
}

if (!gl.map) {
    gl.map = {};
}

//TODO: (status -1), convert `messagesObj` to array type every time
gl.getObject('debug').msg = function (messageObjs) {

    if (!gl.getObject('php.imitation').is_array(messageObjs)) {
        return false;
    }

    for (var v in messageObjs) {
        var aVal = messageObjs[v];
        if (!gl.config.debug) {
            alert(aVal.toString());
        } else {
            gl.log(aVal.toString());
        }
    }
};

//PHP functions in Javascript
gl.getObject('php.imitation').is_array = function (inputArray) {
    //return (inputArray instanceof Array);
    return inputArray
        && !(inputArray.propertyIsEnumerable('length'))
        && typeof inputArray === 'object'
        && typeof inputArray.length === 'number';
};

gl.php.imitation.empty = function (emptyValue) {
    return (emptyValue === ""
        || emptyValue === 0
        || emptyValue === "0"
        || emptyValue === null
        || emptyValue === false
        || (gl.php.imitation.is_array(emptyValue) && emptyValue.length === 0)
    );
};

gl.getObject('map.coordinates').string2array = function (str) {
    //TODO: проверять правильность, убирать пробелы и т.п.
    var stringArr = str.split(';');
    return {
        lat: stringArr[0],
        lng: stringArr[1]
    }
};

gl.getObject('helpers').randomString = function (length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
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

gl.info.debugMsg = function (msg, doAlert) {
    if (gl.config.debug) {
        gl.log(msg);
        if (doAlert) {
            alert('Debug: ' + JSON.stringify(msg));
        }
    } else {
        //TODO: сделать отправку на сервер сообщения об ошибке
        //gl.log(msg);
    }
};


/*var $obj33 = gl.getObject('q.we.rty.yui');
gl.log(['$obj33_1: ', $obj33]);
$obj33.q.tst = 123;
//$obj33.q.we.rty.yui = 'tes str90';
gl.log(['$obj33_2: ', $obj33]);
var $obj44 = gl.getObject('q.we.rty.yui.opas');
gl.log(['$obj44: ', $obj44]);
gl.log(['$obj33_2: ', $obj33]);*/

//TODO: усовершенствовать - добавить отсылку писем и т. д.
gl.assert = function (value, msg) {
    if (!value) {
        msg = 'Error: 0' + (msg ? msg : 'Unknown');
        if (gl.config.debug) {
            alert(msg);
            gl.log(msg);
        } else {
            gl.log(msg);
        }
    }
};

gl.error = function (msg) {
    gl.assert(false, msg);
};

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

gl.inIframe = function () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
};

gl.getObject('object').get = function (obj, key) {
    return key.split('.').reduce(function (o, x) {
        return (typeof o == 'undefined' || o === null) ? o : o[x];
    }, obj);
};

gl.object.has = function (obj, key) {
    return key.split('.').every(function (x) {
        if (typeof obj != 'object' || obj === null || !x in obj)
            return false;
        obj = obj[x];
        return true;
    });
};

gl.object.isFunction = function (obj) {
    return !!(obj && obj.constructor && obj.call && obj.apply);
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

/**
 * Хранилище объектов jQuery.
 *
 * jQueryDE == jQueryDomElements
 */
gl.jQueryDE = {
    list: {},
    addOrOverwrite: function (selector) {
        this.list[selector] = $(selector);

        return this.list[selector].length;
    },
    addNoOverwrite: function (selector, debugAlert) {
        if (typeof this.list[selector] !== 'undefined') {
            if (debugAlert) {
                gl.info.debugMsg('jQuery selector already exists: "' + selector + '"');
            }
            return false;
        }

        this.list[selector] = $(selector);

        return this.list[selector].length;
    },
    get: function (selector) {
        return this.list[selector];
    }
};
gl.jQueryDE.addOrOverwrite('tp');
gl.jQueryDE.get('tp');
gl.jQueryDE.addNoOverwrite('tp');


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
    ifSomethingInStore: function () {
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
    cleanStore: function () {
        localStorage.removeItem('orderFormState');
    },
    restoreFromStore: function () {
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
    restoreFromStoreData: function (data) {
        //debugger;
        //var ps = JSON.parse(data);
        //localStorage.setItem('orderFormState', data);
        gl.container.localStorageObj().unserialize(data);
        this.restoreFromStore();
        return;

        gl.container.localStorageObj().unserialize(ps);
        this.restoreFromStore();
        return;

        var orderFormState;
        try {
            //localStorage.unserialize(orderFormStateJson);
            //gl.container.localStorageObj().unserialize(orderFormStateJson);
            //orderFormStateJson.unse
            //debugger;
            //localStorage = .unserialize(orderFormStateJson);
            orderFormState = JSON.parse(orderFormStateJson);
            //orderFormState = orderFormStateRaw
        } catch (e) {
            alert('err: ' + e);
            return;
        }
        if (!orderFormState) {
            //TODO: handle such error (wrong JSON)
            //localStorage.removeItem('orderFormState');
            alert('!orderFormState');
            return false;
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

        //gl.log('ADD: localStorage.orderFormState: ' + JSON.parse(localStorage.orderFormState).length);
    },
    removeComponentById: function (componentId, completely) {
        completely = typeof completely !== 'undefined' ? completely : false;

        //gl.log('componentId: ' + componentId);

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
            //gl.log("orderFormState[id]: ");
            //gl.log(orderFormState[id]);
            //gl.log("orderFormState[id]['data-id']: " + orderFormState[id]['data']['data-id']);
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

gl.handleJqueryAjaxFail = function (XMLHttpRequest, textStatus, errorThrown, howToHandle) {
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
    } else {
        this.log('UNKNOWN handling type');
    }
};

gl.handleFailCustom = function (message, howToHandle) {
    howToHandle = typeof howToHandle !== 'undefined' ? howToHandle : 'console';
    if (howToHandle == 'alert') {
        alert(message);
    } else if (howToHandle == 'console') {
        this.log('Fail Custom Message: ' + message);
    }
};


$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            } else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }

        init();
    });
});

// Полифил для trim()
if (!String.prototype.trim) {
    (function () {
        // Вырезаем BOM и неразрывный пробел
        String.prototype.trim = function () {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
        };
    })();
}
