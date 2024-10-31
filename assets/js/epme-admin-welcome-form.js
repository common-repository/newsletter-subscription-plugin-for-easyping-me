(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme = $('.epme');

    // Modal Welcome Form
    $(function() {
        var $welcome_form = $('.epme-welcome-form');

        if ($welcome_form.length) {
            var login = $welcome_form.data('login');

            if (!epme_get_cookie('epme-welcome-banner' + login, null)) {
                var date = new Date();

                epme_open_modal($welcome_form);
                date.setTime(date.getTime() + 12*60*60*1000 );
                epme_set_cookie('epme-welcome-banner' + login, 2, date);
            }
        }
    });
    function epme_welcome_form() {
        iFrameResize(
            {
                log: false,
                onMessage: function (ev) {
                    if (ev.message === 'form submited') {
                        epme_welcome_form_complete();
                    }
                },
            }, '.epme-welcome-form__iframe'
        );
    }
    $(function () {
        epme_welcome_form();
    });

    function epme_welcome_form_complete() {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_welcome_form_complete',
                nonce_code: epme_ajaxurl.nonce,
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }

                if (data.message) {
                    epme_show_message(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_welcome_form_complete-@11: '+xhr.status);
                console.error('epme_welcome_form_complete-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    }

    window.epme_welcome_form = epme_welcome_form;
    window.epme_welcome_form_complete = epme_welcome_form_complete;

}($ || window.jQuery));
// end of file