jQuery(document).ready(function() {
    jQuery('.toggle_attribute').on('change',function(){
        var attribute = $(this).metadata().attributeName;
        var dd = jQuery('.configure_attribute_'+attribute).parents('dd');
        var dt = dd.prev('dt');

        if('no'==jQuery(this).val()) {
            dd.hide();
            dt.hide();
        }

        if('yes'==jQuery(this).val()) {
            dd.show();
            dt.show();
        }
    });
});