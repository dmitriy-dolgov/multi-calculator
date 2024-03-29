/**
 * Позиционирование элементов, коррекция стилей и т.п.
 */

/*gl.functions.adjustComponentToSelectHeight = function () {
    var compElem = elems['.vertical-pane .component'];
    var cw = compElem.width();
    elems['.vertical-pane .component .img-wrap'].css({'height': cw + 'px'});
};*/

gl.functions.resizeIframeHeight = function (obj) {
    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
};

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

$('.component-link').mousedown(function (e) {
    //e.preventDefault();

    elems['.vertical-pane'].css('overflow', 'visible');
    //elems['.components-in-stock .collapse-content'].css('overflow', 'visible');
    currentDragElement = $(this);

    /*var elem = $(this);
    elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function() {
      addComponent(elem);
    });*/

    //return false;
});

/*$('.component-link').mouseup(function (e) {
    elems['.vertical-pane'].css('overflow', 'hidden');
    elems['.components-in-stock .collapse-content'].css('overflow', 'hidden');
    currentDragElement = false;
});*/

elems['.wrp-pane .btn-head'].click(function () {
    var btnElem = $(this);

    elems['.wrp-pane .btn-head'].each(function () {
        if (!$(this).is(btnElem)) {
            $(this).parent().removeClass('unwrap');
        }
    });

    btnElem.parent().toggleClass('unwrap');
});

/*elems['.categories-panel.btn-head'].click(function () {
    var btnElem = $(this);

    elems['.wrp-pane .btn-head'].each(function () {
        $(this).parent().removeClass('unwrap');
    });

    elems['.categories-panel.panel-elements-list'].toggleClass('unwrap');
});*/

gl.functions.addOrderToPanel = function () {
    var orderInfoJson = $('#order-info').val();
    var orderInfo = JSON.parse(orderInfoJson);

    //TODO: !!! pizza-name - грубый хак
    var btnHtml = '<div class="elem" data-order-id="' + gl.escapeHtml(orderInfo.result.order_uid)
        + '" data-order-info="' + gl.escapeHtml(orderInfoJson) +
        '" onclick="gl.functions.showOrderPanel(this);return false;" class="">' + gl.escapeHtml($('.pizza-name').text()) + '</div>';
    elems['.customer-orders-panel'].find('.orders-container').append(btnHtml);
    //elems['.customer-orders-panel'].show();
};

gl.functions.minimizeOrderPanel = function () {
    $('#popup-compose-form').modal('hide');
    //gl.functions.restoreOrderPanel();
};

gl.functions.showOrderPanel = function (elem) {
    var jqElem = $(elem);

    $('.customer-orders-panel').toggleClass('unwrap');

    var orderInfo = jqElem.data('order-info');
    gl.functions.fillOrderInfo(orderInfo.result, orderInfo.formData);

    $('#popup-compose-form').modal('show');
};

//TODO: !!! to FIX this hach
gl.functions.restoreOrderPanel = function () {
    var form = $('#order-form-submit');

    form.find('.order-data-container.info-panel').html('Проверьте данные и подтвердите заказ.<br>\n' +
        '<div class="info-message">Заказ будет принят ближайшей пиццерией, о чём вам придет уведомление.</div>\n');

    form.find('.btn-submit-order').text(gl.data['Confirm order']).removeAttr('onclick');
};
