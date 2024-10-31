(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }
    var $body = $('body');

    function transform_table_caption() {
        if ($body.width() < 720) {
            var $epme_table = $('.epme-table');

            $epme_table.each(function () {
                var $this_table = $(this),
                    $table_headers = $('thead th', $this_table),
                    $table_rows = $('tbody tr', $this_table),
                    text = '',
                    $titles = $('.epme-table__new-caption', $this_table);

                if ($titles.length) {
                    return;
                }

                $table_rows.each(function () {
                    var $this_tr = $(this),
                        $td_colspan = $('td[colspan]', $this_tr),
                        $tds = $('td', $this_tr);

                    if ($td_colspan.length) {
                        return;
                    }

                    for (var i = 0; i < $tds.length; i++) {
                        var $this_td = $($tds[i]);
                        var $btn = $('button', $this_td);


                        if (!$btn.length) {
                            if (!$this_td.is(':empty')  ) {
                                text = '<span class="epme-table__new-caption epme-title--caps">' + $table_headers[i].textContent + ' </span>';
                                $this_td.prepend(text);
                            }
                        }
                    }
                });
            });
        } else {
            var $new_captions = $('.epme-table__new-caption');

            if ($new_captions.length !== 0) {
                $new_captions.remove();
            }
        }
    }

    var transform_table_caption_throttle = _.throttle(transform_table_caption, 600);
    $(window).resize(function(){
        transform_table_caption_throttle();
    });
    $(function () {
        $body.on('epme:testers-tab', function () {
            transform_table_caption();
        });
        transform_table_caption();
    });
}($ || window.jQuery));
