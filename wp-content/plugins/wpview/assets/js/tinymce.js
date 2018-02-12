(function ($) {
    $(document).delegate('.mce-btn', 'click', function () {
        $('.wpview_field_parent').parents('#TB_window').css({
            'height': 'auto'
        });
        $('.wpview_field_parent').parents('#TB_ajaxContent').prev().css({
            'padding-left': '15px',
            'font-size': '16px'
        });
        $('.wpview_field_parent').parents('#TB_ajaxContent').css({
            'width': '96%',
            'margin-left': 'auto',
            'margin-right': 'auto',
            'padding-top': '10px'
        });
    });
    tinymce.PluginManager.add('wpView', function (ed, url) {
        ed.addButton('wpView', {
            text: '',
            tooltip: 'wpview',
            image: wpview.url + '/assets/images/wpview.png',
            onclick: function () {
                var w = $(window).width();
                var h = $(window).height();
                var dialogWidth = 600;
                var dialogHeight = 400;
                var H = (dialogHeight < h) ? dialogHeight : h;
                var W = (dialogWidth < w) ? dialogWidth : w;
                tb_show(wpview.dialogTitle, '#TB_inline?width=' + W + '&height=' + H + '&inlineId=wpview_dialog');
            }
        });
    });

    $(document).delegate('#TB_overlay', 'mousedown', function () {
        var content = $('#wpview_dialog').find('fieldset');
        if (!content.length) {
            $('#wpview_dialog').html($(this).next().find('#TB_ajaxContent').html());
        }
    });

    $(document).delegate('#TB_closeWindowButton', 'mousedown', function () {
        var content = $('#wpview_dialog').find('fieldset');
        if (!content.length) {
            $('#wpview_dialog').html($(this).parents('#TB_title').next('#TB_ajaxContent').html());
        }
    });

    $(document).delegate('.put-shortcode', 'mousedown', function () {
        var object = $('.wpview_shortcode_fields_group input[type=radio]:checked');
        if (object) {
            var shortcode = '[wpview type="' + object.attr('data-id') + '"';
            shortcode += object.attr('title') && object.attr('title') !== 'All in one Table' ? ' title="' + object.attr('title') + '"' : '';
            shortcode += object.attr('title') !== 'All in one Table' ? ' view="' + object.parent().next().val() + '"]' : ']';
            var content = object.parents('#TB_ajaxContent').html();
            $('#wpview_dialog').empty();
            $('#wpview_dialog').html(content);
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
            tb_remove();
        }
    });
})(jQuery);