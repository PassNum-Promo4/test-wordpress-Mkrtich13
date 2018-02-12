jQuery(document).ready(function ($) {
    $('audio, video').bind('play', function () {
        for (x = 0; x < $('audio').length; x++) {
            if ($('audio')[x] !== this)
                $('audio')[x].pause();
        }
        for (x = 0; x < $('video').length; x++) {
            if ($('video')[x] !== this)
                $('video')[x].pause();
        }
    });
});