// custom scroll
$(window).bind('resize',
    function () {
        setTimeout(
            function () {
                //elems['.vertical-pane'].data('jsp').reinitialise();

                var vpElem  = elems['.vertical-pane'].data('jsp');
                if (vpElem) {
                    vpElem.reinitialise();
                }

                //elems['.vertical-pane'].css('overflow', 'visible');
            }, 50
        );
    }
);

// Восстановление после удаления артефактов загрузки
$(function () {
    $('.vertical-pane .component-link').css('visibility', 'visible');

    //elems['.vertical-pane'].data('jsp');
});

/*setTimeout(
    function () {
        elems['.vertical-pane'].data('jsp').reinitialise();
        //elems['.vertical-pane'].css('overflow', 'visible');
    }, 5000
);*/

//TODO: костыль - исправить
/*elems['.vertical-pane'].css('overflow', 'visible');
setTimeout(function() {
  elems['.vertical-pane'].css('overflow', 'visible');
}, 1500);*/
