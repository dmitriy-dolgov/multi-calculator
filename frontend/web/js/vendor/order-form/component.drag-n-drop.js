'use strict';

// drag and drop
$('.component').draggable({revert: true, revertDuration: 0});
$('.components-selected-details').droppable({
    /**
     *
     * @param event
     * @param ui
     */
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
                //elems['.components-in-stock .collapse-content'].css('overflow', 'hidden');
                addComponent(currentDragElement);
                currentDragElement = null;
            });
        } else {
            elems['.vertical-pane'].css('overflow', 'hidden');
            //elems['.components-in-stock .collapse-content'].css('overflow', 'hidden');
            addComponent(currentDragElement);
            currentDragElement = null;
        }
    }
});
