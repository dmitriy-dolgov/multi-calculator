$('.ct-sel-elem').change(function () {
    var categoryId = $(this).data('category_id');
    gl.log('categoryId: ' + categoryId);
    if ($(this).val() == 1) {
        elems['.components-in-stock'].find('[data-category_id="' + categoryId + '"]').show();
    } else {
        elems['.components-in-stock'].find('[data-category_id="' + categoryId + '"]').hide();
    }
});
