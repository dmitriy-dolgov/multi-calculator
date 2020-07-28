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
    currentDragElement = $(this);

    /*var elem = $(this);
    elems['.no-components-pane'].fadeOut(componentPresenceFadeInOutTime, function() {
      addComponent(elem);
    });*/

    //return false;
});

$('.standard-pizzas-panel .btn-head').click(function () {
    $('.standard-pizzas-panel').toggleClass('unwrap');
});
$('.standard-orders-panel .btn-head').click(function () {
    $('.standard-orders-panel').toggleClass('unwrap');
});

gl.functions.minimizeOrderPanel = function () {
    var orderInfoJson = $('#order-info').val();
    var orderInfo = JSON.parse(orderInfoJson);

    //TODO: !!! pizza-name - грубый хак
    var btnHtml = '<div data-order-id="' + gl.escapeHtml(orderInfo.result.order_uid)
        + '" data-order-info="' + orderInfoJson +
        '" onclick="gl.functions.showOrderPanel(this);return false;" class="">' + gl.escapeHtml($('.pizza-name').text()) + '</div>';
    elems['.standard-orders-panel'].find('.orders-container').append(btnHtml);
    elems['.standard-orders-panel'].show();

    $('#popup-compose-form').modal('hide');
};

gl.functions.showOrderPanel = function (elem) {

};
