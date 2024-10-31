(function($) {

    // on load
    function mm_thumbnail() {
        var $mm__open_thumb = $('.mm__open-thumb');

        $mm__open_thumb.on('click', function () {
            mm_thumbnail_contain(this);
        });
    }

    // on component settings
    function mm_thumbnail__icon() {
        var $mm__open_thumb = $('.mm-icon.mm__open-thumb');

        $mm__open_thumb.on('click', function () {
            mm_thumbnail_contain(this);
        });
    }

    function mm_thumbnail_contain(el) {
        var $this = $(el),
            $mm_modal__content = $('.mm-modal--thumbnail .mm-modal__content'),
            link = $this.data('img');

        $mm_modal__content.html('<img src="'+link+'">');
        mm_thumbnail_adaptive();
        mm_open_thumb('thumbnail');
    }

    function mm_thumbnail_adaptive() {
        var $mm_modal__content = $('.mm-modal--thumbnail .mm-modal__content img'),
            windowHeight = window.innerHeight,
            windowWidth = window.innerWidth;

        $mm_modal__content.css({
            "max-height": windowHeight*0.96 +"px",
            "max-width": windowWidth*0.94 + "px"
        });
    }

    // Open thumb window
    function mm_open_thumb(modal_id) {
        var $body = $('body');

        $body.addClass('open-thumb').addClass('open-thumb--' + modal_id);
        $body.data('current-thumb', modal_id);
    }

    // Close modal
    function mm_close_thumb_load() {
        var $close_modal = $('.close_thumb_modal');

        $close_modal.on('click', function () {
            mm_close_thumb();
        });
    }

    // Close current thumbnail
    function mm_close_thumb() {
        var $body = $('body'),
            current_modal = $body.data('current-modal');

        $body.removeClass('open-thumb').removeClass('open-thumb--' + current_modal);
    }

    window.mm_thumbnail = mm_thumbnail;
    window.mm_thumbnail_contain = mm_thumbnail_contain;
    window.mm_thumbnail__icon = mm_thumbnail__icon;
    window.mm_close_thumb_load = mm_close_thumb_load;
    window.mm_thumbnail_adaptive = mm_thumbnail_adaptive;
    window.mm_close_thumb = mm_close_thumb;
    window.mm_open_thumb = mm_open_thumb;

})($ || window.jQuery);