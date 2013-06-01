$(document).ready(function() {
    $('.toggle_attribute').on('change',function(){
        var attribute = $(this).metadata().attributeName;
        var dd = $('.configure_attribute_'+attribute).parents('dd');
        var dt = dd.prev('dt');

        if('no'==$(this).val()) {
            dd.hide();
            dt.hide();
        }

        if('yes'==$(this).val()) {
            dd.show();
            dt.show();
        }
    });
    $('.toggle_attribute').trigger('change');
});