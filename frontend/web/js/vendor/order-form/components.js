'use strict';

gl.functions.showPizzeriaInfo = function (pizzeriaId) {
    var framePizzeriaForm = $('#frame-pizzeria-info');
    setTimeout(function () {
        framePizzeriaForm.attr('src', '/vendor/pizzeria-info?id=' + pizzeriaId);

        setTimeout(function () {
            $('#popup-pizzeria-info').modal('show');
        }, 50);
    }, 0);
};

function getAddedComponentsCount() {
    return elems['#order-form .components-selected-details'].find('.added-component').length;
}

function addSameComponent(id, item_select_max, unit_switch_group_id) {
    var handled = false;
    var elemToHandle = false;

    var newElemid = id;
    var maxElems = item_select_max;

    if (unit_switch_group_id && elems['#order-form .components-selected-details'].find('.added-component.switched').length) {
        handled = true;
    } else {
        elems['#order-form .components-selected-details'].find('.added-component').each(function () {
            var elem = $(this);
            var currentElemid = elem.data('id');
            if (currentElemid == newElemid) {
                var amount = parseInt(elem.data('amount'));
                ++amount;
                if (maxElems != '' && amount > maxElems) {
                    alert(gl.data['cant_add_so_many_of_component']);
                    //$('.component[data-id=' + currentElemid + ']').fadeOut();
                    handled = 'no-add';
                    return false;
                }
                if (amount == 2) {
                    elem.append($('<div class="comp-amount">2</div>'));
                } else {
                    elem.find('.comp-amount').text(amount);
                }
                elem.data('amount', amount);

                handled = true;
                elemToHandle = elem;
            }
        });
    }

    if (handled && elemToHandle) {
        //console.log('(handled && elem)');
        elemToHandle[0].scrollIntoView({
            behavior: "smooth", // or "auto" or "instant"
            block: "start" // or "end"
        });
    }

    return handled;
}

function addComponentByData(data, append) {
    gl.functions.addComponentByData(data, append);
}

gl.functions.addComponentByData = function (data, append, noHistory) {
    if (!data) {
        return false;
    }

    if (!noHistory) {
        gl.orderFormHistory.addComponentByData(data, append);
    }

    append = typeof append !== 'undefined' ? append : false;

    var id = data['data-id'];
    var name = data['data-name'];
    var short_name = data['data-short_name'];

    //TODO: test_temp
    //var image = gl.data['domain-admin-schema'] + '://' + gl.data['domain-admin'] + data['data-image'];
    var image = data['data-image'];
    var image_text = data['data-image-text'] ? data['data-image-text'] : '';

    var video = data['data-video'];
    var price = parseFloat(data['data-price']);
    var priceStr = (price).toFixed(2);
    var price_discount = data['data-price_discount'];

    var item_select_min = data['data-item_select_min'];
    var item_select_max = data['data-item_select_max'];
    var unit_name = data['data-unit_name'];
    var unit_value = data['data-unit_value'];
    var unit_value_min = data['data-unit_value_min'];
    var unit_value_max = data['data-unit_value_max'];
    var unit_switch_group_id = data['data-unit_switch_group_id'];
    var unit_switch_group_name = data['data-unit_switch_group_name'];

    var html = '';  /*'<div class="added-component" data-id="' + id
            + '" data-toggle="tooltip" data-placement="top" data-html="true" title="'
            + gl.escapeHtml(short_name) + '<br>' + gl.escapeHtml(priceStr) + '">'
            + '<div class="image" style="background-image:url(' + gl.escapeHtml(image) + ')"></div>'
            //+ '<div class="price">' + gl.escapeHtml(price) + '</div>'
            + '</div>';
        elems['.components-selected'].append(html);*/

    if (gl.data.isWebLocal) {
        elems['.video'].css('background-image', 'url(/video/construct/default-local.gif)');
    } else {
        elems['.video'].css('background-image', 'url(' + video + ')');
    }

    //var addResult = addSameComponent(elem);
    var addResult = addSameComponent(id, item_select_max, unit_switch_group_id);
    if (addResult === 'no-add') {
        setTimeout(function () {
            gl.functions.handleComponetsCount();
        }, 0);
        return true;
    }

    if (!addResult) {

        /*var currentPrice = elems['.total-price'].data('total_price');
        var newPrice = (parseFloat(currentPrice) + price).toFixed(2);
        elems['.total-price'].data('total_price', newPrice);
        elems['.total-price'].text(newPrice + ' gl.data['currency']');*/

        $('.added-component[data-id=' + id + ']').tooltip();

        var priceHtml = price ? (gl.beautifyPrice(priceStr, gl.data['currency'])) : gl.data['for-free'];

        var classSwitch = '';
        if (unit_switch_group_id) {
            classSwitch = 'switched';
        }

        var imageTextHtml = image_text ? ('<div class="image-text">' + image_text + '</div>') : '';

        /*for(var q =0; q < 7; ++q) {
            imageTextHtml += '<div class="dot"></div>';
        }*/

        html = '<div class="added-component no-opacity ' + classSwitch + '" data-id="' + id + '" data-amount="1" data-serial_id="' + totalAddedElements + '">'
            //+ '<div class="image-wrapper">'
            + '<div class="nice-line"></div>'
            + '<div class="image" style="background-image:url(' + gl.escapeHtml(image) + ')">' + imageTextHtml + '</div>'
            //+ '</div>'

            //TODO: pz_comp
            /*+ '<div class="image over" style="background-image:url(' + gl.escapeHtml(image)
            + ')" data-toggle="tooltip" data-placement="top" data-html="true" title="'
            + gl.escapeHtml(name) + '<br>' + gl.escapeHtml(priceStr) + ' ' + gl.data['currency'] + '"></div>'*/

            + '<div class="text-string">'
            + '<div class="short_name">' + gl.escapeHtml(short_name) + '</div>'
            + '<div class="dots">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>'
            + '<div class="price">' + priceHtml + '</div>'
            + '</div>';
        if (!item_select_min) {
            html += '<a href="#" class="btn-delete elem-manage white-shadow" onclick="gl.functions.orderDeleteElem(this); return false;" title="' + gl.data['delete'] + '"></a>';
        }
        if (unit_switch_group_id) {
            html += '<a href="#" class="btn-switch-component elem-manage white-shadow" data-toggle="modal" data-target="#switch-component-modal" title="' + gl.data['switch-component'] + '"></a>';
        }
        //var dataParameters = 'data-elem_index="' + totalAddedElements + '" data-amount="1" ';
        var dataParameters = 'data-elem_index="' + totalAddedElements + '"';
        for (var paramId in data) {
            dataParameters += paramId + '="' + gl.escapeHtml(data[paramId]) + '" ';
        }
        html += '<div class="data-container" ' + dataParameters + '>'
            + '<input type="hidden" name="ShopOrderForm[components][' + totalAddedElements + '][component_id]" value="' + gl.escapeHtml(id) + '">'
            + '<input type="hidden" class="price-value" name="ShopOrderForm[components][' + totalAddedElements + '][price]" value="' + gl.escapeHtml(price) + '">'
            + '<input type="hidden" name="ShopOrderForm[components][' + totalAddedElements + '][price_discount]" value="' + gl.escapeHtml(price_discount) + '">'
            + '<input type="hidden" name="ShopOrderForm[components][' + totalAddedElements + '][name]" value="' + gl.escapeHtml(name) + '">'
            + '<input type="hidden" name="ShopOrderForm[components][' + totalAddedElements + '][short_name]" value="' + gl.escapeHtml(short_name) + '">'
            //+ '<input type="hidden" name="ShopOrderForm[components][' + totalAddedElements + '][amount]" value="1">'
            + '</div>'
            + '</div>';

        ++totalAddedElements;

        //elems['.components-selected-details'].append(html);
        if (append) {
            elems['.component-holder'].append(html);
        } else {
            elems['.component-holder'].prepend(html);
        }

        $('.sidebar .components-selected-details').scrollTop(0);

        setTimeout(function () {
            elems['.components-selected-details'].find('.added-component[data-id="' + id + '"]').removeClass('no-opacity');
        }, 0);

        elems['.components-selected-details'].find('[data-id=' + id + '] .image').tooltip();
    }

    gl.functions.orderCalculatePrice();

    //TODO: можно здесь ломать код чтобы увидеть незагруженные товарв
    if (gl.functions.showUpgoingText) {
        gl.functions.showUpgoingText('Добавлен');
    }

    setTimeout(function () {
        gl.functions.handleComponetsCount();
    }, 0);

    return true;
};

function addComponent(elem, append) {
    if (!elem) {
        return false;
    }

    append = typeof append !== 'undefined' ? append : false;

    var data = {
        'data-id': elem.data('id'),
        'data-name': elem.data('name'),
        'data-short_name': elem.data('short_name'),
        'data-image': elem.data('image'),
        'data-video': elem.data('video'),
        'data-price': elem.data('price'),
        'data-price_discount': elem.data('price_discount'),
        'data-item_select_min': elem.data('item_select_min'),
        'data-item_select_max': elem.data('item_select_max'),
        'data-unit_name': elem.data('unit_name'),
        'data-unit_value': elem.data('unit_value'),
        'data-unit_value_min': elem.data('unit_value_min'),
        'data-unit_value_max': elem.data('unit_value_max'),
        'data-unit_switch_group_id': elem.data('unit_switch_group_id'),
        'data-unit_switch_group_name': elem.data('unit_switch_group_name')
    };

    //var result = addComponentByData(data, append);

    /*setTimeout(function () {
        gl.functions.handleComponetsCount();
    }, 0);*/

    return addComponentByData(data, append);
}

/*gl.functions.switchComponent = function (elem) {

}*/

gl.functions.orderDeleteElem = function (elem, completely) {

    completely = typeof completely !== 'undefined' ? completely : false;

    var componentContainer = $(elem).closest('.added-component');

    var currentElemid = componentContainer.data('id');
    var amount = parseInt(componentContainer.data('amount'));

    gl.orderFormHistory.removeComponentById(currentElemid, completely);

    if (!completely) {
        --amount;
        componentContainer.data('amount', amount);
        if (amount != 0) {
            if (amount == 1) {
                componentContainer.find('.comp-amount').remove();
            } else {
                componentContainer.find('.comp-amount').text(amount);
            }
            gl.functions.orderCalculatePrice();
            return;
        }
    }

    componentContainer.find('.comp-amount').remove();

    componentContainer.css({'transition': 'none', '-webkit-transition': 'none'});
    componentContainer.fadeOut(400, function () {
        componentContainer.remove();

        gl.functions.orderCalculatePrice();
        gl.functions.handleComponetsCount();
    });
};

gl.functions.handleComponetsCount = function () {
    if (getAddedComponentsCount()) {
        elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function () {
            elems['.capt-price'].fadeIn(componentPresenceFadeInOutTime, function () {
                //elems['.capt-price'].css('display', 'inline-block');
                elems['.capt-price'].css('display', 'block');
            });
        });
    } else {
        elems['.capt-price'].fadeOut(componentPresenceFadeInOutTime, function () {
            elems['.no-components-pane'].fadeIn(componentPresenceFadeInOutTime);
        });
    }
};

gl.functions.orderCalculatePrice = function () {
    var price = 0.;
    elems['#order-form .components-selected-details'].find('.added-component').each(function () {
        var elem = $(this);
        var priceValue = elem.find('.price-value').val();
        var amount = parseInt(elem.data('amount'));

        price += parseFloat(priceValue) * 100 * amount;
    });

    price /= 100;

    if (!price) {
        elems['.total-price'].data('total_price', 0);
        elems['.total-price'].text(' -- ');
        //elems['.btn-order'].prop('disabled', true);
    } else {
        price = price.toFixed(2);
        elems['.total-price'].data('total_price', price);
        elems['.total-price'].html(gl.beautifyPrice(price, gl.data['currency']));
        elems['.btn-order'].prop('disabled', false);
    }
};

$('.switch-component').click(function () {
    var elem = $(this);
    elem.parent().find('.switch-component').removeClass('selected');
    elem.addClass('selected');

    $('.added-component.switched').remove();
    addComponent(elem, true);
});

//TODO: рефакторинг - убрать дубляж
if (getAddedComponentsCount()) {
    elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function () {
        elems['.capt-price'].fadeIn(componentPresenceFadeInOutTime, function () {
            //elems['.capt-price'].css('display', 'inline-block');
            elems['.capt-price'].css('display', 'block');
        });
    });
} else {
    elems['.capt-price'].fadeOut(componentPresenceFadeInOutTime, function () {
        elems['.no-components-pane'].fadeIn(componentPresenceFadeInOutTime);
    });
}

function deleteAllComponents() {
    gl.orderFormHistory.cleanStore();
    elems['#order-form .components-selected-details'].find('.added-component').each(function () {
        gl.functions.orderDeleteElem($(this), true);
    });
}

$('.panel-elements-list .elem-pi').click(function () {
    var elem = $(this);
    $('.pizza-name').text(elem.data('name'));
    deleteAllComponents();

    $('.standard-pizzas-panel').toggleClass('unwrap');

    //TODO: pz_comp ?
    //TODO: дождаться удаления перед тем как класть
    setTimeout(function () {
        totalAddedElements = 0;

        var newComponents = elem.data('components');
        for (var id in newComponents) {
            addComponentByData(newComponents[id]);
        }
    }, 800);
});

var rateRange = document.getElementById('playback-rate');
var shapers = [].slice.call(document.querySelectorAll('div'));
var DURATION = 200000;
var animations = [];

shapers.forEach(function(s, i) {
    var animation = s.animate([
        {offsetDistance: 0},
        {offsetDistance: '100%'}
    ], {
        duration: DURATION,
        delay: -i / shapers.length * DURATION,
        iterations: Infinity
    });
    animations.push(animation);
});

if (rateRange) {
    rateRange.addEventListener('input', function (e) {
        var rate = parseFloat(e.currentTarget.value);
        console.log(rate);
        animations.forEach(function (animation) {
            animation.playbackRate = rate;
        })
    });
}