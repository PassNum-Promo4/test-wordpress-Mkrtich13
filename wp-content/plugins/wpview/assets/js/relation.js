jQuery(document).ready(function ($) {
	if ($('.wpview_relation_view .wpview_relation').length) {
		var itemsCount = $('.wpview_relation_view .wpview_relation').length;
		for (var i = 0; i < itemsCount; i++) {
			var img = $('.wpview_relation_view .wpview_relation:eq(' + i + ') img');
			img.parents('.wpview_relation').width(img.width());
			img.parents('.wpview_relation').height(img.height());
		}
	} else if ($('.wpview_relation_field .wpview_relation').length) {
		var itemsCount = $('.wpview_relation_field .wpview_relation').length;
		for (var i = 0; i < itemsCount; i++) {
			var img = $('.wpview_relation_field .wpview_relation:eq(' + i + ') img');
			img.parents('.wpview_relation').width(img.width());
			img.parents('.wpview_relation').height(img.height());
		}
	}
});