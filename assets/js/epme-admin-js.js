(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme = $('.epme'),
        $epme_cont = $('.epme-cont');

    // Date picker init
    $(function () {
        var $datepicker = $('.epme-datepicker');

        if ($.datepicker !== void 0) {
            $datepicker.datepicker();
            $.datepicker.setDefaults({
                dateFormat: 'dd-mm-yy',
                firstDay: 1,
                showAnim: 'slideDown',
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: '',
            });
        }
    });

    // Init interface texts.
    $(function () {
        var $texts = $('#epme-texts');

        if ($texts.length) {
            try {
                window._epme_texts = JSON.parse($texts.text());
            } catch (err) {
                epme_show_error(err);
            }
        }
    });
    function epme_texts(key) {
        return (window._epme_texts !== void 0 && window._epme_texts[key] !== void 0) ? window._epme_texts[key] : '';
    }

    // Init Multi Select
    $(function () {
        var $multi_select = $('.epme-multi-select');

        if ($multi_select.length) {
            $multi_select.select2({
                'placeholder': epme_texts('sub-placeholder'),
                'tags': true,
                'tokenSeparators': [',', ' '],
                'width' : '100%',
                'closeOnSelect': false,
            });
        }
    });

    // Validate number input
    $(function () {
        var $epme_number_input = $('.epme-number-input');

        $epme_number_input.keydown(function (event) {
            // Allow: backspace, delete, tab, escape, '.'
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 190 ||
                // Allow: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||
                // Allow: home, end, влево, вправо
                (event.keyCode >= 35 && event.keyCode <= 39)) {
                // Do nothing
                return;
            } else {
                // Make sure that this is a figure and stop the event keypress
                if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                    event.preventDefault();
                }
            }
        });
    });

    // Init emojiPicker
    $(function () {
        var $init_textarea = $('.epme-textarea-emoji');

        if ($init_textarea.length) {
            $init_textarea.emojiPicker({
                height: '300px',
                width:  '450px'
            });
        }
    });

    // UTC time to Local
    function epme_utc_time_to_local() {
       var $time = $('.epme-date--gmt-to-local');

       $time.each(function () {
           var $this = $(this),
               date;

           $this.trigger('epme.date-before');
           switch (this.tagName) {
               case 'input':
               case 'INPUT':
                   date = new Date($this.val());
                   if (date instanceof Date && !isNaN(date)) {
                       $this.val(date.toLocaleString());
                   }
                   break;
               default:
                   date = new Date($this.text());
                   if (date instanceof Date && !isNaN(date)) {
                       $this.text(date.toLocaleString());
                   }
           }
           $this.trigger('epme.date-complete');
       });
    }
    function epme_utc_time_to_local_with_masc() {
       var $time = $('[data-utc][data-masc]');

       $time.each(function () {
           var $this = $(this),
               this_date = $this.data('utc'),
               this_masc = $this.data('masc'),
               date = new Date(this_date);

           $this.trigger('epme.date-before-with-masc');
           switch (this.tagName.toLowerCase()) {
               case 'input':
                   if (date instanceof Date && !isNaN(date)) {
                       $this.val(date.empe_format(this_masc));
                   }
                   break;
               default:
                   if (date instanceof Date && !isNaN(date)) {
                       $this.text(date.empe_format(this_masc));
                   }
           }
           $this.trigger('epme.date-complete-with-masc');
       });
    }
    $(function () {
        epme_utc_time_to_local();
        epme_utc_time_to_local_with_masc();
    });

    // Return user's time zone.
    function epme_get_current_time_zone() {
        var x = new Date();
        return -x.getTimezoneOffset() / 60;
    }

    // Open modal
    $(function () {
        $body.on('click', '.epme-modal-link', function () {
            var $this = $(this),
                this_link = $this.data('link'),
                this_title = $this.data('title'),
                $epme_modal = $(this_link);

            if (this_title !== void 0 && this_title) {
                var $title = $('.mdl-dialog__title', $epme_modal);

                $title.data('title-origin', $title.text());
                $title.text(this_title);
            }
            epme_open_modal($epme_modal);
        });
        $body.on('click', '.epme-modal__close', function () {
            var $this = $(this),
                $epme_modal = $this.closest('.epme-modal'),
                modal_remove_class = $epme_modal.data('remove-class'),
                $title = $('.mdl-dialog__title', $epme_modal),
                title_origin = $title.data('title-origin'),
                $btns = $('.mdl-dialog__actions .mdl-button', $epme_modal);

            if (title_origin !== void 0 && title_origin) {
                $title.text(title_origin);
            }
            if (!$epme.hasClass('epme--modal-loading')) {
                $epme_modal.hide();
            }

            if (modal_remove_class !== void 0 && modal_remove_class) {
                $epme_modal.removeClass(modal_remove_class);
            }

            $btns.each(function () {
                var $this = $(this),
                    this_text_origin = $this.data('text-origin');

                if (this_text_origin !== void 0 && this_text_origin) {
                    $this.text(this_text_origin);
                }
            });
        });
    });

    // Return true if variable exist and not empty
    function epme_is_exist(data) {
        switch (true) {
            case (typeof data === "object") :
                return !!(data !== void 0 && Object.keys(data).length);
            default:
                return !!(data !== void 0 && data);
        }
    }

    // Return true if input changed
    function epme_is_value_changed($elem) {
        return ($elem.data('prev-val') === void 0 || $elem.data('prev-val') != $elem.val());
    }

    function epme_loading_start(param) {
        if ($epme.length) {
            $epme.addClass('epme--loading');
            if (param) {
                $epme.addClass('epme--loading-1');
            }
        }
    }
    function epme_loading_stop() {
        if ($epme.length) {
            $epme.removeClass('epme--loading').removeClass('epme--loading-1');
        }
    }

    function epme_modal_loading_start() {
        if ($epme.length) {
            $epme.addClass('epme--modal-loading');
        }
    }
    function epme_modal_loading_stop() {
        if ($epme.length) {
            $epme.removeClass('epme--modal-loading');
        }
    }

    function epme_show_message(message) {
        if (message) {
            var snackbarContainer = document.querySelector('#message-box');

            snackbarContainer.MaterialSnackbar.showSnackbar({message: message});
        }
        console.log(message);
    }
    function epme_show_warning(message) {
        try {
            if (message) {
                var snackbarContainer = document.querySelector('#error-box');

                snackbarContainer.MaterialSnackbar.showSnackbar(message);
            }
            console.warn(message);
        } catch (err) {
            console.error('easyping.me plugin: ' + err);
        }
    }
    function epme_show_error(message) {
        try {
            if (message) {
                var snackbarContainer = document.querySelector('#error-box');

                snackbarContainer.MaterialSnackbar.showSnackbar({message: message});
            }
        } catch (err) {
            console.error('easyping.me plugin: ' + err);
        } finally {
            console.error('easyping.me plugin: ' + message);
        }
    }
    function epme_check_response(response) {
        if (response.status === void 0 || response.status.type === void 0 || response.status.type === 'error') {
            epme_show_error(response.status.cause);
            if (response.status.cause === 'Not authorization') {
                location.reload();
            }
            return true;
        }
        return false;
    }

    // Dialog modal
    function epme_open_dialog_modal(cont, title, btn_save, btn_close, new_class) {
        var $epme_modal = $('.epme-modal--dialog'),
            $cont = $('.mdl-dialog__content', $epme_modal),
            remove_class = $epme_modal.data('remove-class');

        if (epme_is_exist(title)) {
            var $title = $('.mdl-dialog__title', $epme_modal);

            $title.data('title-origin', $title.text());
            $title.text(title);
        }

        if (epme_is_exist(remove_class)) {
            $epme_modal.removeClass(remove_class);
        }

        if (epme_is_exist(btn_save)) {
            var $btn_save = $('.epme-modal__save', $epme_modal);

            $btn_save.data('text-origin', $btn_save.text());
            $btn_save.text(btn_save);
        }

        if (epme_is_exist(btn_close)) {
            var $btn_close = $('.epme-modal__close', $epme_modal);

            $btn_close.data('text-origin', $btn_close.text());
            $btn_close.text(btn_close);
        }

        if (epme_is_exist(cont)) {
            $cont.html(cont);
        } else {
            $cont.html('');
        }

        if (epme_is_exist(new_class)) {
            $epme_modal.addClass(new_class);
            $epme_modal.data('remove-class', new_class);
        }

        epme_open_modal($epme_modal);
    }

    // Open Dialog modal
    function epme_open_modal($modal) {
        $modal.css({
            'display' : 'flex'
        });
    }

    // Close Dialog modal
    function epme_close_dialog_modal() {
        var $epme_modal = $('.epme-modal--dialog');

        epme_close_modal($epme_modal);
    }

    // Close the Modal
    function epme_close_modal($epme_modal) {
        $epme_modal.hide();
    }

    // Close the Modals
    function epme_close_all_modal() {
        var $epme_modal = $('.epme-modal');

        $epme_modal.hide();
    }

    // Dialog modal for change balance
    function epme_on_change_balance_modal(amount, balance, modal_class) {
        epme_open_dialog_modal('You have '+amount+' eur. on your account. '+balance+' eur. will be charged.', 'Withdrawals', 'I agree', 'Cancel', modal_class);
    }

    function epme_diff(a, b) {
        var r = b.filter(function(i){return a.indexOf(i) < 0;}),
            r2 = a.filter(function(i){return b.indexOf(i) < 0;});

        return !!(r.length + r2.length);
    }

    function epme_get_cookie(name, def) {
        var cookies = (document.cookie || '').split('; ');
        for (var i=0; i < cookies.length; i++) {
            var c = cookies[i].split('=');
            if (c[0] === name) return decodeURIComponent(c[1]);
        }
        return def;
    }

    function epme_set_cookie (name, value, expires, path) {
        var cookie = name + '=' + encodeURIComponent(value) + '; Expires=' + expires.toGMTString();
        if (path === undefined) path='/';
        cookie += '; Path='+path;
        document.cookie = cookie;
    }

    function epme_delete_cookie(name) {
        var date = new Date();

        date.setTime(date.getTime() - 1);
        epme_set_cookie(name, '', date);
    }

    window.epme_is_exist = epme_is_exist;
    window.epme_texts = epme_texts;
    window.epme_utc_time_to_local = epme_utc_time_to_local;
    window.epme_utc_time_to_local_with_masc = epme_utc_time_to_local_with_masc;
    window.epme_get_current_time_zone = epme_get_current_time_zone;
    window.epme_is_value_changed = epme_is_value_changed;
    window.epme_loading_start = epme_loading_start;
    window.epme_loading_stop = epme_loading_stop;
    window.epme_modal_loading_start = epme_modal_loading_start;
    window.epme_modal_loading_stop = epme_modal_loading_stop;
    window.epme_show_message = epme_show_message;
    window.epme_show_warning = epme_show_warning;
    window.epme_show_error = epme_show_error;
    window.epme_check_response = epme_check_response;
    window.epme_open_dialog_modal = epme_open_dialog_modal;
    window.epme_open_modal = epme_open_modal;
    window.epme_close_dialog_modal = epme_close_dialog_modal;
    window.epme_close_modal = epme_close_modal;
    window.epme_close_all_modal = epme_close_all_modal;
    window.epme_on_change_balance_modal = epme_on_change_balance_modal;
    window.epme_diff = epme_diff;
    window.epme_get_cookie = epme_get_cookie;
    window.epme_set_cookie = epme_set_cookie;
    window.epme_delete_cookie = epme_delete_cookie;

}($ || window.jQuery));
// end of file