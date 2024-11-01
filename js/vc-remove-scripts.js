function remove_bc() {
    jQuery('.vc_edit-form-tab-control').each(function () {
        jQuery(this).on('click', function () {
            let data_ui_element_target = jQuery(this).children('button.vc_ui-tabs-line-trigger').data('vc-ui-element-target');
            setTimeout(function () {
                let curent_style_button = [];
                let curent_style_button_single;
                let button = jQuery(data_ui_element_target + ' button.wp-color-result');
                jQuery(data_ui_element_target + ' button.wp-color-result').next('.wp-picker-input-wrap').each(function () {
                    let picker = jQuery(this).prev('button');
                    jQuery(this).children('label').children('input.wpb_vc_param_value').change(function () {
                        if (0 > picker[0].getAttribute('style').indexOf('!important')) {
                            curent_style_button_single = picker[0].getAttribute('style').replace(/;/g, "!important;");
                            picker[0].setAttribute('style', curent_style_button_single);
                        }
                    });

                });
                for (let i = 0; i < button.length; i++) {
                    if (0 > button[i].getAttribute('style').indexOf('!important')) {
                        curent_style_button[i] = button[i].getAttribute('style').replace(/;/g, "!important;");
                        button[i].setAttribute('style', curent_style_button[i]);
                    }
                }

            }, 2000)
        });
    });
}

function proportion_value() {
    jQuery('.vc_edit-form-tab-control').each(function () {
        jQuery(this).on('click', function () {

            setTimeout(function () {
                jQuery('#vc_edit-form-tab-2 input[name="h"]').bind("keyup change", function (e) {
                    var width2 = parseInt(jQuery('#vc_edit-form-tab-2 input[name="h"]').val() * 1.5);
                    jQuery('#vc_edit-form-tab-2 input[name="w"]').val(width2);
                })

                jQuery('#vc_edit-form-tab-2 input[name="w"]').bind("keyup change", function (e) {
                    var height2 = parseInt(jQuery('#vc_edit-form-tab-2 input[name="w"]').val() / 1.5);
                    jQuery('#vc_edit-form-tab-2 input[name="h"]').val(height2);
                })
            }, 2000)

        });
    });
}

function true_cart_popup() {
    jQuery('.vc_checkbox-label').each(function () {
        if (jQuery(this).children('input#cpu-true').prop('checked') == true) {

            jQuery('input#sb-true').prop('checked', 'checked');
            jQuery('input#sb-true').prop('disabled', 'disabled');


        }
    })

    jQuery('input#cpu-true').on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('input#sb-true').prop('checked', 'checked');
            jQuery('input#sb-true').prop('disabled', 'disabled');
        } else {
            jQuery('input#sb-true').prop('checked', false);
            jQuery('input#sb-true').prop('disabled', false);
        }
    });
}



