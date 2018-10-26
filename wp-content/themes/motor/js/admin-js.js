"use strict";
jQuery(function(jQuery) {
    // Image Upload Button
    jQuery('.custom_upload_image_button').click(function() {
        var formfield = jQuery(this).siblings('.custom_upload_image');
        var preview = jQuery(this).siblings('.custom_preview_image');
        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        window.send_to_editor = function(html) {
            var o = jQuery(html);
            if (o.is('a')){
                o = o.find('img');
            }
            var imgurl = o.attr('src');
            var classes = o.attr('class');
            var id = classes.replace(/(.*?)wp-image-/, '');
            formfield.val(id);
            preview.attr('src', imgurl);
            tb_remove();
        };
        return false;
    });
    jQuery('.custom_clear_image_button').click(function() {
        var defaultImage = jQuery(this).parent().siblings('.custom_default_image').text();
        jQuery(this).parent().siblings('.custom_upload_image').val('');
        jQuery(this).parent().siblings('.custom_preview_image').attr('src', defaultImage);
        return false;
    });


    // Repeatable
    jQuery('.repeatable-add').click(function() {
        var field = jQuery(this).closest('td').find('.custom_repeatable li:last').clone(true);
        var fieldLocation = jQuery(this).closest('td').find('.custom_repeatable li:last');
        jQuery('input.custom_field_r', field).val('').attr('name', function(index, name) {
            return name.replace(/(\d+)/, function(fullMatch, n) {
                return Number(n) + 1;
            });
        });
        field.insertAfter(fieldLocation, jQuery(this).closest('td'));
        return false;
    });

    jQuery('.repeatable-remove').click(function(){
        jQuery(this).parent().remove();
        return false;
    });

    jQuery('.custom_repeatable').sortable({
        opacity: 0.6,
        revert: true,
        cursor: 'move',
        handle: '.sort'
    });

    
    // Datepicker
    if (jQuery('.motor-datepicker').length > 0) {
        jQuery(".motor-datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    }


    // Product Description
    if (jQuery('.motor-product-motor_product_editor input[type=radio]').length > 0) {
        jQuery('.motor-product-motor_product_editor input[type=radio]').on('change', function () {
            var radio_val = jQuery(this).val();
            var desc_editor = jQuery('.motor-product-motor_product_description');
            if (radio_val == 'default') {
                desc_editor.fadeOut();
            } else if (radio_val == 'custom') {
                desc_editor.fadeIn();
            }
        });
    }
    
});