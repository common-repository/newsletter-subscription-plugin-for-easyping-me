(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme_cont = $('.epme-cont');

    $(function () {
        $body.on('click', '.epme-tab', function () {
            var $this = $(this),
                $header = $this.parent('*'),
                $tabs = $('.epme-tab', $header),
                $par = $this.closest('.mdl-tabs'),
                $content_blocks = $par.children('.mdl-tabs__panel'),
                $content_target = $($this.data('target'));

            $tabs.removeClass('epme-is-active');
            $content_blocks.removeClass('epme-is-active');

            $this.addClass('epme-is-active');
            $content_target.addClass('epme-is-active');
        });
    });

}($ || window.jQuery));
// end of file