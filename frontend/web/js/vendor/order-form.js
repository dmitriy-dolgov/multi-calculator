var totalAddedElements = 0;

var currentDragElement = null;

var componentPresenceFadeInOutTime = 200;

var userProfiles = gl.data.activeUserProfilesJs;

var userGeoPosition = gl.data.userGeoPosition;

var elems = {
    '.total-price': $('.total-price'),
    '.components-selected': $('.components-selected'),
    '.components-selected-details': $('.components-selected-details'),
    '#order-form .components-selected-details': $('#order-form .components-selected-details'),
    '.btn-order': $('.btn-order'),
    '.ingredients': $('.ingredients'),
    '.no-components-pane': $('.no-components-pane'),
    '.vertical-pane': $('.vertical-pane'),
    '.capt-price': $('.capt-price'),
    '.component-holder': $('.component-holder'),
    '.video': $('.video'),
    '#elems-container': $('#elems-container'),
    '#order-form-submit': $("#order-form-submit"),
    '.vertical-pane .component': $('.vertical-pane .component'),
    '.vertical-pane .component .img-wrap': $('.vertical-pane .component .img-wrap'),
};

gl.functions.adjustComponentToSelectHeight = function () {
    var compElem = elems['.vertical-pane .component'];
    var cw = compElem.width();
    elems['.vertical-pane .component .img-wrap'].css({'height': cw + 'px'});
};

gl.functions.resizeIframeHeight = function (obj) {
    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
};

gl.functions.setLogged = function () {
    location.reload();
};

gl.functions.correctGeolocation = function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            gl.data.worldMap.flyTo([lat, lng]);
        });
    } else {
        alert(gl.data['geolocation-is-not-accessible']);
    }
};

gl.functions.correctGeolocation();

gl.functions.showPizzeriaInfo = function (pizzeriaId) {
    var framePizzeriaForm = $('#frame-pizzeria-info');
    setTimeout(function () {
        framePizzeriaForm.attr('src', '/vendor/pizzeria-info?id=' + pizzeriaId);

        setTimeout(function () {
            $('#popup-pizzeria-info').modal('show');
        }, 50);
    }, 0);
};

gl.functions.SelectProviders = {
    'select-all': function () {
        $('.select-providers input[type="checkbox"]').prop('checked', true);
    },
    'unselect-all': function () {
        $('.select-providers input[type="checkbox"]').prop('checked', false);
    },
    'nearest': function () {
        alert('В разработке');
        return false;
    }
};

function getAddedComponentsCount() {
    return elems['#order-form .components-selected-details'].find('.added-component').length;
}

function addSameComponent(id, item_select_max, unit_switch_group_id) {
    var handled = false;

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
            }
        });
    }

    return handled;
}

function addComponentByData(data, append) {
    if (!data) {
        return false;
    }

    append = typeof append !== 'undefined' ? append : false;

    var id = data['data-id'];
    var name = data['data-name'];
    var short_name = data['data-short_name'];
    var image = gl.data['domain-admin-schema'] + '://' + gl.data['domain-admin'] + data['data-image'];
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

    elems['.video'].css('background-image', 'url(' + video + ')');

    //var addResult = addSameComponent(elem);
    var addResult = addSameComponent(id, item_select_max, unit_switch_group_id);
    if (addResult === 'no-add') {
        return true;
    }

    if (!addResult) {

        /*var currentPrice = elems['.total-price'].data('total_price');
        var newPrice = (parseFloat(currentPrice) + price).toFixed(2);
        elems['.total-price'].data('total_price', newPrice);
        elems['.total-price'].text(newPrice + ' gl.data['currency']');*/

        $('.added-component[data-id=' + id + ']').tooltip();

        var priceHtml = price ? (gl.escapeHtml(priceStr) + ' ' + gl.data['currency']) : gl.data['for-free'];

        var classSwitch = '';
        if (unit_switch_group_id) {
            classSwitch = 'switched';
        }

        html = '<div class="added-component no-opacity ' + classSwitch + '" data-id="' + id + '" data-amount="1">'
            + '<div class="image" style="background-image:url(' + gl.escapeHtml(image) + ')"></div>'

            + '<div class="image over" style="background-image:url(' + gl.escapeHtml(image)
            + ')" data-toggle="tooltip" data-placement="top" data-html="true" title="'
            + gl.escapeHtml(name) + '<br>' + gl.escapeHtml(priceStr) + ' ' + gl.data['currency'] + '"></div>'

            + '<div class="text-string">'
            + '<div class="short_name">' + gl.escapeHtml(short_name) + '</div>'
            + '<div class="dots">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</div>'
            + '<div class="price">' + priceHtml + '</div>'
            + '</div>';
        if (!item_select_min) {
            html += '<a href="#" class="btn-delete elem-manage" onclick="gl.functions.orderDeleteElem(this); return false;" title="' + gl.data['delete'] + '"></a>';
        }
        if (unit_switch_group_id) {
            html += '<a href="#" class="btn-switch-component elem-manage" data-toggle="modal" data-target="#switch-component-modal" title="' + gl.data['switch-component'] + '"></a>';
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
            + '</div>'
            + '</div>';

        ++totalAddedElements;

        //elems['.components-selected-details'].append(html);
        if (append) {
            elems['.component-holder'].append(html);
        } else {
            elems['.component-holder'].prepend(html);
        }
        setTimeout(function () {
            elems['.components-selected-details'].find('.added-component[data-id="' + id + '"]').removeClass('no-opacity');
        }, 0);

        elems['.components-selected-details'].find('[data-id=' + id + '] .image').tooltip();
    }

    gl.functions.orderCalculatePrice();

    return true;
}

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

    return addComponentByData(data, append);
}

/*gl.functions.switchComponent = function (elem) {

}*/

gl.functions.orderDeleteElem = function (elem, completely) {

    completely = typeof completely !== 'undefined' ? completely : false;

    var componentContainer = $(elem).closest('.added-component');

    var currentElemid = componentContainer.data('id');
    var amount = parseInt(componentContainer.data('amount'));

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

        if (getAddedComponentsCount()) {
            elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function () {
                elems['.capt-price'].fadeIn(componentPresenceFadeInOutTime, function () {
                    elems['.capt-price'].css('display', 'inline-block');
                });
            });
        } else {
            elems['.capt-price'].fadeOut(componentPresenceFadeInOutTime, function () {
                elems['.no-components-pane'].fadeIn(componentPresenceFadeInOutTime);
            });
        }
    });
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
        elems['.total-price'].text(price + ' ' + gl.data['currency']);
        elems['.btn-order'].prop('disabled', false);
    }
};

$('.component-link').mousedown(function (e) {
    //e.preventDefault();

    elems['.vertical-pane'].css('overflow', 'visible');
    currentDragElement = $(this);


    /*var elem = $(this);
    elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function() {
      addComponent(elem);
    });*/

    //return false;
});

elems['.btn-order'].click(function (e) {
    if (!getAddedComponentsCount()) {
        elems['.ingredients'].removeClass('red');
        setTimeout(function () {
            elems['.ingredients'].addClass('red');
        }, 50);

        return false;
    }

    gl.functions.composeOrder();
});


// custom scroll
$(window).bind('resize',
    function () {
        setTimeout(
            function () {
                elems['.vertical-pane'].data('jsp').reinitialise();
                //elems['.vertical-pane'].css('overflow', 'visible');
            }, 20
        );
    }
);


// drag and drop
$('.component').draggable({revert: true, revertDuration: 0});
$('.components-selected-details').droppable({
    over: function (event, ui) {
        $(this).addClass('hover');
    },
    out: function (event, ui) {
        $(this).removeClass('hover');
    },
    drop: function (event, ui) {
        $(this).removeClass('hover');
        //TODO: проверять кол-во элементов
        if (!getAddedComponentsCount()) {
            elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function () {

                elems['.capt-price'].fadeIn(componentPresenceFadeInOutTime, function () {
                    $(this).css('display', 'inline-block');
                });

                elems['.vertical-pane'].css('overflow', 'hidden');
                addComponent(currentDragElement);
                currentDragElement = null;
            });
        } else {
            elems['.vertical-pane'].css('overflow', 'hidden');
            addComponent(currentDragElement);
            currentDragElement = null;
        }
    }
});

//TODO: костыль - исправить
/*elems['.vertical-pane'].css('overflow', 'visible');
setTimeout(function() {
  elems['.vertical-pane'].css('overflow', 'visible');
}, 1500);*/


/*$("#video").jPlayer({
    ready: function () {
      $(this).jPlayer("setMedia", {
        m4v: "/video/1.mp4"
        //ogv: "vid/TEDxPhoenix-KelliAnderson-DisruptiveWonderforaChange.ogv",
        //poster: "vid/TEDxPhoenix-KelliAnderson-DisruptiveWonderforaChange.png"
      });
      $(this).jPlayer("play", 1);
    },
    swfPath: "/js",
    //supplied: "m4v,ogv"
    supplied: "m4v",
    loop: true,
    backgroundColor: "#000",
    size: {
         width: "100%",
         height: "auto"
    }
});*/

$('.standard-pizzas-panel .btn-head').click(function () {
    $('.standard-pizzas-panel').toggleClass('unwrap');
});

gl.functions.composeOrder = function () {
    var html = '';
    var priceTotal = 0;

    this.serialNumber = 1;

    var orderDataContainerId = 'order-data-container-' + this.serialNumber;

    html += '<div id="' + orderDataContainerId + '">'
        + elems['#elems-container'].find('.order-data-container-wrp').html()
        + '<div class="order-data-container">'
        + '<div class="title">' + gl.data['ingredients'] + '</div>';
    elems['#order-form .components-selected-details'].find('.added-component').each(function () {
        var dataContainer = $(this).find('.data-container');
        //var elem_index = dataContainer.data('elem_index');
        var name = dataContainer.data('name');
        var short_name = dataContainer.data('short_name');
        var image = dataContainer.data('image');
        var price = dataContainer.data('price');
        //var priceStr = (price).toFixed(2);
        var price_discount = dataContainer.data('price_discount');
        var unit_name = dataContainer.data('unit_name');
        var unit_value = dataContainer.data('unit_value');

        var amount = $(this).data('amount');

        priceTotal += parseFloat(price) * parseInt(amount);

        html += '<div class="preview-element">'
            + '<div class="name">' + gl.escapeHtml(short_name) + '</div>';
        var amountHtml = '';
        if (amount > 1) {
            //TODO: ед. => международные единицы, а также в единицы измерения типа граммы
            amountHtml = '<div class="amount">' + amount + ' ед.</div>';
        }
        var priceText = price ? price : gl.data['for-free'];
        html += '<div class="price">'
            + amountHtml + gl.escapeHtml(priceText)
            + (price ? (' <span class="currency-sign">' + gl.data['currency'] + '</span>') : '')
            + '</div>'
            + dataContainer.html()
            + '</div>';
    });
    html += '<div class="preview-element total-price-element">'
        + gl.data['price:'] + '<div class="price">' + gl.escapeHtml(priceTotal.toFixed(2)) + ' <span class="currency-sign">' + gl.data['currency'] + '</span></div>'
        + '</div>';
    html += '</div>';

    html += '<div class="map-placeholder"></div>';

    var placesMapId = 'places-map-' + this.serialNumber;
    var mapMarkers = [];
    var initialMapParameters = {
        latitude: userGeoPosition.lat,
        longitude: userGeoPosition.lon,
        zoom: 11
    };

    html += '<div class="order-data-container select-providers" style="display: none">'
        + elems['#elems-container'].find('.providers-handling-wrp').html();
    //+ '<div class="places-map" id="' + placesMapId + '"></div>';
    //+ '<fieldset><legend>' + gl.data['Pizzerias'] + '</legend>';
    var firstCycle = true;
    for (var upId in userProfiles) {
        //gl.log("userProfiles[upId].icon_image_path: " + userProfiles[upId].icon_image_path);
        if (userProfiles[upId].company_lat_long) {
            var latLong = userProfiles[upId].company_lat_long.split(';');
            var icon = null;
            if (userProfiles[upId].icon_image_path) {
                icon = L.icon({
                    iconUrl: userProfiles[upId].icon_image_path,
                    iconSize: [userProfiles[upId].icon_image_size.width, userProfiles[upId].icon_image_size.height]
                });
            }
            //gl.log("latLong.length: " + latLong.length);
            if (latLong.length > 1) {
                //mapMarkers.push({latitude: parseFloat(latLong[0].replace(',', '.')), longitude: parseFloat(latLong[1].replace(',', '.'))});
                mapMarkers.push({
                    id: userProfiles[upId].id,
                    latitude: latLong[0],
                    longitude: latLong[1],
                    icon: icon,
                    popupHtml: userProfiles[upId].popupHtml
                });
                /*if (firstCycle) {
                    //initialMapParameters = {latitude: parseFloat(latLong[0].replace(',', '.')), longitude: parseFloat(latLong[1].replace(',', '.')), zoom: 13};
                    initialMapParameters = {latitude: latLong[0], longitude: latLong[1], zoom: 11};
                }*/
            }
        }

        var firstHtml = '';
        if (firstCycle) {
            firstHtml = 'first';
            firstCycle = false;
        }
        html += '<div class="pizzeria-panel"><label class="selector ' + firstHtml + '">'
            + '<input type="checkbox" checked="checked" value="' + userProfiles[upId].id + '" name="ShopOrderForm[user_ids][]">'
            + gl.escapeHtml(userProfiles[upId].name)
            + '</label><button type="button" class="btn-pizzeria-info" onclick="gl.functions.showPizzeriaInfo(' + userProfiles[upId].id + ')">'
            + gl.data['info'] + '</button></div>';
    }
    html += ''  //</fieldset>'
        + '</div></div>';

    elems['#order-form-submit'].find(".component-container").html(html);

    elems['#order-form-submit'].unbind('submit');
    elems['#order-form-submit'].submit(function (e) {
        e.preventDefault();

        var hasError = false;
        var errorMessages = [];

        var elem = $(this);
        elem.find('.preview-element-yd').removeClass('error');
        elem.find('.error-messages').hide();

        var deliver_addressElem = elem.find('[name="ShopOrderForm[deliver_address]"]');
        var deliver_address = $.trim(deliver_addressElem.val());
        deliver_addressElem.val(deliver_address);

        var deliver_customer_nameElem = elem.find('[name="ShopOrderForm[deliver_customer_name]"]');
        var deliver_customer_name = $.trim(deliver_customer_nameElem.val());
        deliver_customer_nameElem.val(deliver_customer_name);

        var deliver_phoneElem = elem.find('[name="ShopOrderForm[deliver_phone]"]');
        var deliver_phone = $.trim(deliver_phoneElem.val());
        deliver_phoneElem.val(deliver_phone);

        var deliver_emailElem = elem.find('[name="ShopOrderForm[deliver_email]"]');
        var deliver_email = $.trim(deliver_emailElem.val());
        deliver_emailElem.val(deliver_email);

        var deliver_commentElem = elem.find('[name="ShopOrderForm[deliver_comment]"]');
        var deliver_comment = $.trim(deliver_commentElem.val());
        deliver_commentElem.val(deliver_comment);

        if (!deliver_address) {
            deliver_addressElem.parent().addClass('error');
            hasError = true;
            errorMessages.push(gl.data['delivery-address']);
        }
        if (!deliver_customer_name) {
            deliver_customer_nameElem.parent().addClass('error');
            hasError = true;
            errorMessages.push(gl.data['your-name']);
        }
        if (!deliver_phone && !deliver_email) {
            deliver_phoneElem.parent().addClass('error');
            deliver_emailElem.parent().addClass('error');
            hasError = true;
            errorMessages.push(gl.data['email-or-phone']);
        }

        //TODO: включить
        //if (hasError) {
        if (0) {
            var errText = '<span class="err-title">' + gl.data['please-enter'] + '</span><br>';
            for (var id in errorMessages) {
                errText += errorMessages[id] + '<br>';
            }
            elem.find('.error-messages').html(errText).fadeIn();
            return false;
        }

        /*var formHtml = $('#order-form-submit-wrapper').html();

        var ifrm = document.getElementById('frm-confirmed-order');
        ifrm = ifrm.contentWindow || ifrm.contentDocument.document || ifrm.contentDocument;
        ifrm.document.open();
        ifrm.document.write(formHtml);
        ifrm.document.getElementById('order-form-submit').submit();
        ifrm.document.close();

        //elem.css('display', 'none');
        elem.fadeOut(200, function () {
            $('#frm-confirmed-order').fadeIn();
        });*/

        var formData = elem.serialize();

        $.post('/site/order-create-ajax', formData, function (result) {
            if (result.status == 'success') {
                elem.find('.info-panel').html('<h3>' + gl.data['Order confirmed.'] + '</h3><div class="info-message red">'
                    + gl.data['Expect pizzeria notifications.'] + '</div>');
                elem.find('.btn-submit-order').text(gl.data['Minimize the order panel']).attr('onclick', 'alert("В разработке");return false;');
                var orderDataDataElem = elem.find('.order-data-data');
                orderDataDataElem.animate({opacity: 0}, function () {
                    //TODO: mark_1
                    var html = gl.data['Recipient:'] + ' ' + gl.escapeHtml(deliver_customer_name) + '<br>'
                        + gl.data['Delivery address:'] + ' ' + gl.escapeHtml(elem.find('[name="ShopOrderForm[deliver_city_id]"]').val())
                        + ', ' + gl.escapeHtml(deliver_address) + '<br>';
                    if (deliver_phone) {
                        html += gl.data['Phone:'] + ' ' + gl.escapeHtml(deliver_phone) + '<br>';
                    }
                    if (deliver_email) {
                        html += 'Email: ' + gl.escapeHtml(deliver_email) + '<br>';
                    }
                    if (deliver_comment) {
                        html += gl.data['Your comment:'] + ' ' + gl.escapeHtml(deliver_comment) + '<br>';
                    }
                    html += 'ID заказа: ' + gl.escapeHtml(result.order_uid);
                    orderDataDataElem.html(html);
                    orderDataDataElem.animate({opacity: 1});

                    setInterval(function () {
                        $.get('/shop-order/order-status', {orderId: result.order_uid}, function (result) {
                            if (result.status == 'success' && result.data['order-status'] == 'offer-sent-to-customer') {
                                for (var id in gl.functions.placesMap.prototype.allMovingMarkers) {
                                    gl.functions.placesMap.prototype.allMovingMarkers[id].removeFrom(gl.data.worldMap.map);
                                }
                                gl.functions.placesMap.prototype.allMovingMarkers = [];

                                for (id in gl.functions.placesMap.prototype.allPolylines) {
                                    gl.functions.placesMap.prototype.allPolylines[id].removeFrom(gl.data.worldMap.map);
                                }
                                gl.functions.placesMap.prototype.allPolylines = [];
                            }
                        });
                    }, 5000);
                });
            } else {
                alert(result.msg ? result.msg : 'Unknown error');
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
        });

        return false;
    });

    $('#popup-compose-form').modal('show');

    setTimeout(function () {
        $('#' + orderDataContainerId + ' .map-placeholder').html('<div class="places-map" id="' + placesMapId + '"></div>');

        setTimeout(function () {
            $('.order-data-container [name="ShopOrderForm[deliver_address]"]').suggestions({
                token: '3b0831fece6038806811a6eaef5843755d0ae9a4',
                type: 'ADDRESS',
                onSelect: function (suggestion) {
                    //console.log(suggestion);
                    //console.log("geo_lat: " + suggestion.data.geo_lat);
                    gl.data.worldMap.moveCustomerMarker(suggestion.data.geo_lat, suggestion.data.geo_lon);
                    gl.data.worldMap.connectMarkersWithCustomer();
                }
            });

            gl.data.worldMap = new gl.functions.placesMap(placesMapId, initialMapParameters);
            gl.data.worldMap.addMarkersToMap(mapMarkers);

            $('.input-deliver_city').val(userGeoPosition.name_ru);

            var citySelect = $('.select-deliver_city_id');
            citySelect.find('option[value="' + userGeoPosition.id + '"]').attr('selected', 'true');

            citySelect.unbind('change');
            citySelect.change(function () {
                var option = $(this).find(':selected');
                gl.data.worldMap.flyTo([option.data('lat'), option.data('lon')]);
            });
        }, 150);

    }, 0);

    ++this.serialNumber;
};

gl.functions.placesMap = function (id, initialMapParameters) {
    this.map = L.map(id).setView([initialMapParameters.latitude, initialMapParameters.longitude], initialMapParameters.zoom);

    var pulsingIcon = L.icon.pulse({iconSize: [15, 15], color: 'green', fillColor: 'red'});
    //this.customerMarker = this.addMarkerByCoords(initialMapParameters.latitude, initialMapParameters.longitude, this.icons.customerIcon);
    this.customerMarker = this.addMarkerByCoords(initialMapParameters.latitude, initialMapParameters.longitude, pulsingIcon);

    /*gl.log('this.globalZIndex 0: ' + gl.functions.placesMap.globalZIndex);
    ++gl.functions.placesMap.globalZIndex;

    var customerMarker = this.customerMarker;
    this.customerMarker.on('click', function (e) {
        gl.log('this.globalZIndex 3: ' + gl.functions.placesMap.globalZIndex);
        customerMarker.setZIndexOffset(++gl.functions.placesMap.globalZIndex);
    });*/

    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 18
    }).addTo(this.map);
};

//gl.functions.placesMap.globalZIndex = 0;

gl.functions.placesMap.prototype.moveCustomerMarker = function (newLan, newLon) {
    var newLatLng = new L.LatLng(newLan, newLon);
    this.customerMarker.setLatLng(newLatLng);
};

gl.functions.placesMap.prototype.flyTo = function (lanLon) {
    this.map.flyTo(lanLon);
};

gl.functions.placesMap.prototype.allMarkers = [];
gl.functions.placesMap.prototype.allMovingMarkers = [];
gl.functions.placesMap.prototype.allPolylines = [];

gl.functions.placesMap.prototype.addMarkerByCoords = function (lat, lon, icon, popupHtml) {
    var newMarker;

    var latLng = L.latLng(lat, lon);
    if (icon) {
        newMarker = new L.marker(latLng, {icon: icon}).addTo(this.map);
    } else {
        newMarker = new L.marker(latLng).addTo(this.map);
    }

    if (popupHtml) {
        newMarker.bindPopup(popupHtml);
    }

    this.allMarkers.push(newMarker);

    /*newMarker.on('click', function (e) {
        gl.log('gl.functions.placesMap.globalZIndex 5: ' + gl.functions.placesMap.globalZIndex);
        newMarker.setZIndexOffset(++gl.functions.placesMap.globalZIndex);
    });*/

    return newMarker;
};

gl.functions.placesMap.prototype.icons = {
    defaultPizzeria: L.icon({
        iconUrl: '/img/map/default-pizzeria.png',
        //shadowUrl: 'leaf-shadow.png',

        iconSize: [50, 30] // size of the icon
        //shadowSize: [50, 64], // size of the shadow
        //iconAnchor: [22, 59], // point of the icon which will correspond to marker's location
        //shadowAnchor: [4, 62],  // the same for the shadow
        //popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
    }),
    customerIcon: L.icon({
        iconUrl: '/img/map/customer.gif',
        iconSize: [35, 35]
    }),
    carIcon: L.icon({
        iconUrl: '/img/map/sedan-car-model.svg',
        iconSize: [25, 25]
    }),
    movingTarget: L.icon({
        iconUrl: '/img/map/target.gif',
        iconSize: [11, 11]
    }),
};

gl.functions.placesMap.prototype.addMarkersToMap = function (markers) {
    this.markers = [];
    if (markers.length) {
        for (var mId in markers) {
            var icon = markers[mId].icon ? markers[mId].icon : this.icons.defaultPizzeria;
            this.markers.push({
                id: markers[mId].id,
                marker: this.addMarkerByCoords(markers[mId].latitude, markers[mId].longitude, icon, markers[mId].popupHtml),
            });
            // ++gl.functions.placesMap.globalZIndex;
            // gl.log('this.globalZIndex 2: ' + gl.functions.placesMap.globalZIndex);
        }
    }

    // gl.log('this.globalZIndex 1: ' + gl.functions.placesMap.globalZIndex);
    // this.customerMarker.setZIndexOffset(gl.functions.placesMap.globalZIndex);

    var allMarkersGroup = new L.featureGroup(this.allMarkers);
    this.map.fitBounds(allMarkersGroup.getBounds());

    this.connectMarkersWithCustomer();
};

gl.functions.placesMap.prototype.connectMarkersWithCustomer = function () {
    //this.customerMarksConnectors = [];

    for (var i in this.map._layers) {
        if (this.map._layers[i]._path != undefined) {
            try {
                this.map.removeLayer(this.map._layers[i]);
            } catch (e) {
                console.log("problem with " + e + this.map._layers[i]);
            }
        }
    }

    var geoJsonFeatureCollection = {
        type: 'FeatureCollection',
        features: []
    };

    var customerLatLon = this.customerMarker.getLatLng();
    for (var mId in this.markers) {
        var mrkLanLng = this.markers[mId].marker.getLatLng();
        /*var polylinePoints = [
            [customerLatLon.lat, customerLatLon.lng],
            [mrkLanLng.lat, mrkLanLng.lng]
        ];
        var newPolyline = L.polyline(polylinePoints, {weight: 1, opacity: .6, color: 'gray'});
        gl.functions.placesMap.prototype.allPolylines.push(newPolyline);
        newPolyline.addTo(this.map);*/

        geoJsonFeatureCollection.features.push({
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [customerLatLon.lng, customerLatLon.lat]
                },
                "properties": {
                    "origin_id": 0,
                    "origin_lon": customerLatLon.lng,
                    "origin_lat": customerLatLon.lat,
                    "destination_id": this.markers[mId].id,
                    "destination_lon": mrkLanLng.lng,
                    "destination_lat": mrkLanLng.lat
                }
            }
        );

        /*var myMovingMarker = L.Marker.movingMarker(polylinePoints, [1000], {
            autostart: true,
            loop: true,
            icon: this.icons.movingTarget
            //icon: this.icons.carIcon
        });
        gl.functions.placesMap.prototype.allMovingMarkers.push(myMovingMarker);
        myMovingMarker.addTo(this.map);
        //myMovingMarker.start();*/
    }

    var exampleFlowmapLayer = L.canvasFlowmapLayer(geoJsonFeatureCollection, {
        originAndDestinationFieldIds: {
            originUniqueIdField: 'origin_id',
            originGeometry: {
                x: 'origin_lon',
                y: 'origin_lat'
            },
            destinationUniqueIdField: 'destination_id',
            destinationGeometry: {
                x: 'destination_lon',
                y: 'destination_lat'
            }
        },

        // some custom options
        pathDisplayMode: 'selection',
        animationStarted: true,
        animationEasingFamily: 'Cubic',
        animationEasingType: 'In',
        animationDuration: 2000
    }).addTo(this.map);

    exampleFlowmapLayer.selectFeaturesForPathDisplayById('origin_id', 0, true, 'SELECTION_NEW');
};

gl.functions.doOrder = function () {
    var frameOrderForm = $('#frame-order-form');
    //frameOrderForm.on("load", function() {
    //alert(1);
    //});
    //frameOrderForm.attr('src', gl.data['frame-order-form_src']);
    setTimeout(function () {
        var orderForm = frameOrderForm.contents().find('#order-form');
        orderForm.html($('#order-form').html());
        orderForm.submit();

        elems['#order-form .components-selected-details'].find('.added-component .image.over').each(function () {
            var elem = $(this);
            var ovenFlame = $('.oven-flame');

            var positionStart = elem.offset();
            var positionEnd = ovenFlame.offset();

            elem.css({left: positionStart.left + "px", top: positionStart.top + "px"});
            elem.addClass('flying');
            elem.animate({
                top: positionEnd.top + (ovenFlame.height() / 1.3) + "px",
                left: positionEnd.left + (ovenFlame.width() / 2) + "px",
                width: 0,
                height: 0
            }, 20, 'swing');
        });

        setTimeout(function () {
            $('#popup-order-form').modal('show');
        }, 1500);
    }, 0);
};

$('.switch-component').click(function () {
    var elem = $(this);
    elem.parent().find('.switch-component').removeClass('selected');
    elem.addClass('selected');

    $('.added-component.switched').remove();
    addComponent(elem, true);
});


eval(gl.data.initialJSCode);


gl.functions.orderCalculatePrice();

//TODO: рефакторинг - убрать дубляж
if (getAddedComponentsCount()) {
    elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function () {
        elems['.capt-price'].fadeIn(componentPresenceFadeInOutTime, function () {
            elems['.capt-price'].css('display', 'inline-block');
        });
    });
} else {
    elems['.capt-price'].fadeOut(componentPresenceFadeInOutTime, function () {
        elems['.no-components-pane'].fadeIn(componentPresenceFadeInOutTime);
    });
}

function deleteAllComponents() {
    elems['#order-form .components-selected-details'].find('.added-component').each(function () {
        gl.functions.orderDeleteElem($(this), true);
    });
}

$('.pizzas-list .elem').click(function () {
    var elem = $(this);
    $('.pizza-name').text(elem.data('name'));
    deleteAllComponents();

    totalAddedElements = 0;

    var newComponents = elem.data('components');
    for (var id in newComponents) {
        addComponentByData(newComponents[id]);
    }
});
