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

gl.functions.composeOrder = function () {
    var html = '';
    var priceTotal = 0;

    this.serialNumber = 1;

    var orderDataContainerId = 'order-data-container-' + this.serialNumber;

    gl.functions.restoreOrderPanel();

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
        var serialId = $(this).data('serial_id');

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
            + '<input type="hidden" name="ShopOrderForm[components][' + serialId + '][amount]" value="' + amount + '">'
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
                //gl.functions.websocket.send({newOrderId: result.order_uid});
                gl.functions.longpoll.waitForMerchantOrderAccept({orderId: result.order_uid});

                /*gl.functions.fillOrderInfo(result, {
                    deliver_customer_name: deliver_customer_name,
                    deliver_address: deliver_address,
                    deliver_phone: deliver_phone,
                    deliver_email: deliver_email,
                    deliver_comment: deliver_comment
                });*/
                var formDataArr = elem.serializeArray();
                var formDataArrIndexed = {};
                $.map(formDataArr, function (n, i) {
                    formDataArrIndexed[n['name']] = n['value'];
                });

                //gl.log('formDataArrIndexed:');
                //gl.log(formDataArrIndexed);

                formDataArrIndexed.deliverCityName = elem.find('[name="ShopOrderForm[deliver_city_id]"]').val();

                gl.functions.fillOrderInfo(result, formDataArrIndexed);
                gl.functions.addOrderToPanel();
            } else {
                alert(result.msg ? result.msg : gl.data['Unknown error. Please try again later or refer to administrator.']);
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            //alert('status:' + XMLHttpRequest.status + ', status text: ' + XMLHttpRequest.statusText);
            gl.handleJqueryAjaxFail(XMLHttpRequest, textStatus, errorThrown, 'alert');
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

gl.functions.fillOrderInfo = function (result, formData) {
    var elem = elems['#order-form-submit'];

    elem.find('#order-id').val(result.order_uid);
    elem.find('#order-info').val(JSON.stringify({
        result: result,
        formData: formData
    }));

    elem.find('.info-panel').html('<h3>' + gl.data['Order confirmed.'] + '</h3><div class="info-message red">'
        + gl.data['Expect pizzeria notifications.'] + '</div>');
    elem.find('.btn-submit-order').text(gl.data['Minimize the order panel']).attr('onclick', 'gl.functions.minimizeOrderPanel();return false;');
    var orderDataDataElem = elem.find('.order-data-data');
    orderDataDataElem.animate({opacity: 0}, function () {
        //TODO: mark_1
        var html = gl.data['Recipient:'] + ' ' + gl.escapeHtml(formData['ShopOrderForm[deliver_customer_name]']) + '<br>'
            + gl.data['Delivery address:'] + ' ' + gl.escapeHtml(formData.deliverCityName)
            + ', ' + gl.escapeHtml(formData['ShopOrderForm[deliver_address]']) + '<br>';
        if (formData['ShopOrderForm[deliver_phone]']) {
            html += gl.data['Phone:'] + ' ' + gl.escapeHtml(formData['ShopOrderForm[deliver_phone]']) + '<br>';
        }
        if (formData['ShopOrderForm[deliver_email]']) {
            html += 'Email: ' + gl.escapeHtml(formData['ShopOrderForm[deliver_email]']) + '<br>';
        }
        if (formData['ShopOrderForm[deliver_comment]']) {
            html += gl.data['Your comment:'] + ' ' + gl.escapeHtml(formData['ShopOrderForm[deliver_comment]']) + '<br>';
        }
        html += gl.data['Order ID:'] + ' ' + gl.escapeHtml(result.order_uid);
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
};

// Устанавливает необходимые данные когда продавец взял заказ в обработку (начал готовить)
gl.functions.setUpPaneOnOrderAccepted = function (orderId, merchantData) {
    var result = false;

    var container = $('.orders-container .elem');
    //var container = $('.orders-container [data-order-id="' + orderId + '"]');
    if (container.length) {
        var orderInfoJson = container.data('order-info');
        gl.log(['orderInfoJson', orderInfoJson]);
        //var orderInfoObj = JSON.parse(orderInfoJson);
        var orderInfoObj = orderInfoJson;
        orderInfoObj.orderStatus = 'accepted-by-merchant';
        //container.data('order-info', JSON.stringify(orderInfoObj));
        container.data('order-info', orderInfoObj);

        //TODO: здесь мигает окно "заказы" и если окно с текущим заказом открыто, то мигает и оно
        $('#popup-compose-form .modal-content').addClass('blinking-border-order-accepted');
        /*setTimeout(function () {
            $('#popup-compose-form .modal-content').removeClass('blinking-border');
        }, 7000);*/

        //TODO: перевод
        var html = '<h3>Заказ взят в обработку.</h3>'
            + '<div class="info-message">Пиццерия: ' + gl.escapeHtml(merchantData.name) + '</div>'
            + '<div class="info-message red">Ожидайте когда пицца будет передана курьеру.</div>';
        elems['#order-form-submit'].find('.order-data-container.info-panel').html(html);

        result = true;
    }

    return result;
};


if (gl.orderFormHistory.ifSomethingInStore) {
    gl.orderFormHistory.restoreFromStore();
} else {
    eval(gl.data.initialJSCode);
}

gl.functions.orderCalculatePrice();
