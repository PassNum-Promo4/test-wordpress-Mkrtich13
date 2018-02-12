jQuery(document).ready(function ($) {
    if ($('#wpview_admin_page').length) {
        $('input[name=wpview_choose_views]').click(function () {
            if ($('input[value=display_together]').is(':checked') || $('input[value=display_together_before_content]').is(':checked')) {
                $('#wpview_display_all_apart').addClass('wpview_hidden');
            } else if ($('input[value=display_apart]').is(':checked')) {
                $('#wpview_display_all_apart').removeClass('wpview_hidden');
            }
        });
        $('.wpview_selected').parents('.for_all').addClass('wpview_selected_parent');
        $('.fieldset_label_view select').change(function () {
            if ($(this).val() === 'none') {
                $(this).parents('.for_all').removeClass('wpview_selected_parent');
            } else {
                $(this).parents('.for_all').addClass('wpview_selected_parent');
            }
        });
        $('#wpview_plugin_names label img').click(function () {
            if (!$(this).hasClass('wpview_not_installed_checkbox')) {
                var plugin_name = $(this).attr('id');
                $('img#fielset_logo_' + plugin_name).toggle();
                $(this).toggleClass('wpview_checkbox');
                if ($(this).prev().is(":checked")) {
                    $('img#fielset_logo_' + plugin_name).attr("data-show", 'false');
                } else {
                    $('img#fielset_logo_' + plugin_name).attr("data-show", 'true');
                }
                $('.for_all img[data-show=false]').parents('fieldset').addClass('wpview_hidden');
                $('.for_all img[data-show=true]').parents('fieldset').removeClass('wpview_hidden');
            }
            if ($('.for_all img[data-show=true]').length === 0) {
                $('.wpview_settings').addClass('wpview_hidden');
            } else {
                $('.wpview_settings').removeClass('wpview_hidden');
            }
        });

        for (var i = 0; i < 5; i++) {
            var pluginLogoId = $("#wpview_plugin_names label img").eq(i).attr('id');
            if ($('#' + pluginLogoId).prev().is(":checked")) {
                $('img#fielset_logo_' + pluginLogoId).attr("data-show", 'true');
            } else {
                $('img#fielset_logo_' + pluginLogoId).attr("data-show", 'false');
            }
        }
    } else if ($('#wpview_admin_page_colors_and_styles').length) {
        $('.wpview_color_picker').colorPicker();
        $(document).delegate('#wpview_colors_and_styles h3', 'click', function () {
            $(this).next().slideToggle();
            $(this).toggleClass('wpview_colors_group_close');
        });
    }
    $('.wpview_shortcode_fields_group select').click(function (){
        $(this).prev().children('input[type=radio]').attr('checked', 'checked');
    });
});

