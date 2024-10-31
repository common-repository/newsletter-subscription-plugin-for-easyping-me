(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme_cont = $('.epme-cont'),
        $new_widget = $('.epme-new-widget', $epme_cont),
        prev_class = 'epme-prev',
        $new_widget__prev = $('.'+prev_class, $new_widget);

    // Clear preview box
    function epme_preview_clear() {
        var $epme_prev__cont = $('.epme-prev__cont');

        $epme_prev__cont.hide();
    }

    // Render Preview
    function epme_preview_init() {
        var preview_info = epme_preview_info_init();

        if (epme_is_exist(preview_info) && epme_is_exist(preview_info.type)) {
            epme_preview_clear();

            if (!epme_is_exist(window.preview_info)) {
                window.preview_info = preview_info;
            }

            if (!epme_is_exist(window.preview_info.guid) && epme_is_exist(window.preview_info.widget.guid)) {
                window.preview_info.guid = window.preview_info.widget.guid;
            }
            if (!epme_is_exist(window.preview_info.id) && epme_is_exist(window.preview_info.widget.id)) {
                window.preview_info.id = window.preview_info.widget.id;
            }

            if (epme_is_exist(preview_info.channels) && epme_is_exist(window.preview_info) && !epme_is_exist(window.preview_info.init_channels)) {
                window.preview_info.init_channels = preview_info.channels;
            }

            switch (preview_info.type) {
                case 'text' :
                    epme_preview_type_text(preview_info);
                    break;
                case 'button' :
                    if (preview_info.text !== void 0 && preview_info.text) {
                        epme_preview_type_text(preview_info);
                    }
                    epme_preview_type_button(preview_info);
                    break;
                case 'color-button' :
                    if (preview_info.text !== void 0 && preview_info.text) {
                        epme_preview_type_text(preview_info);
                    }
                    epme_preview_type_button(preview_info);
                    epme_preview_type_color(preview_info);
                    break;
            }
        }
    }

    // Show Text for Preview box
    function epme_preview_type_text(preview_info) {
        try {
            if (epme_is_exist(preview_info.text)) {
                var $cont = $('.epme-prev__cont--text');

                $cont.html(preview_info.text);
                $cont.show();
            }
        } catch (err) {
            epme_show_error(err);
        }
    }

    // Show Button for Preview box
    function epme_preview_type_button(preview_info) {
        try {
            if (epme_is_exist(preview_info.widget.guid) || epme_is_exist(window.preview_info.guid)) {
                var $box = $('.epme-prev__cont--button'),
                    $cont = $('.epme-prev__cont--button > div');

                epme_create_widget(preview_info, $cont);
                $box.show();
            }
        } catch (err) {
            epme_show_error(err);
        }
    }

    // Show Button for Preview box
    function epme_preview_type_color(preview_info) {
        try {
            if (preview_info.color_picker !== void 0) {
                var $cont = $('.epme-prev__cont--color-picker'),
                    $input = $('.epme-color-picker--preview', $cont),
                    $label = $('.epme-prev__color-label', $cont);

                if (window.preview_info === void 0 || window.preview_info.color_picker === void 0 || window.preview_info.color_picker.color === void 0) {
                    if (preview_info.color_picker.color !== void 0 && preview_info.color_picker.color) {
                        window.preview_info.color_picker = {color : preview_info.color_picker.color};
                        $input.val(preview_info.color_picker.color);
                        $input.trigger('keyup');
                    }
                }

                if (preview_info.color_picker.label !== void 0 && preview_info.color_picker.label) {
                    $label.html(preview_info.color_picker.label);
                    $label.show();
                } else {
                    $label.hide();
                }

                $cont.show();
            }
        } catch (err) {
            epme_show_error(err);
        }
    }

    // Select background of Preview
    $(function () {
        $body.on('change', '.epme-color-picker--preview', function () {
            var $this = $(this),
                this_val = $this.val(),
                $epme_prev = $this.closest('.epme-prev');

            if (this_val) {
                if (this_val.indexOf('#') === 0) {
                    this_val = this_val.slice(1);
                }

                var r = parseInt(this_val.slice(0, 2), 16),
                    g = parseInt(this_val.slice(2, 4), 16),
                    b = parseInt(this_val.slice(4, 6), 16);

                $epme_prev.css({
                    'background' : '#' + this_val,
                });

                if ((r * 0.299 + g * 0.587 + b * 0.114) > 186) {
                    $epme_prev.removeClass('epme-prev--dark');
                } else {
                    $epme_prev.addClass('epme-prev--dark');
                }
            }
        });
    });

    // Show Button for Preview box
    function epme_create_widget(preview_info, $cont) {
        try {
            if (window.epme_widget === void 0) {
                if (!epme_is_exist(window.preview_info)) {
                    window.preview_info = preview_info;
                }
                if (!epme_is_exist(window.preview_info.widget)) {
                    window.preview_info.widget = preview_info.widget;
                }
                
                epme_actual_information_from_form();
                window.preview_info.widget.selector = "#brnnrn-subscription-button-" + window.preview_info.guid;

                $cont.html('<div id="brnnrn-subscription-button-'+ window.preview_info.guid +'"></div>');
                window.epme_widget = BRNNRNSubscriptionButton.create(epme_filter_preview_info(window.preview_info.widget));
            } else {
                epme_actual_information_from_form();
                window.epme_widget(epme_filter_preview_info(window.preview_info.widget));
            }
        } catch (err) {
            epme_show_error(err);
        }
    }

    // live refresh the Widget
    $(function () {
        $body.on('change', '.epme-live-refresh-widget--change', epme_refresh_widget);
        $body.on('keyup', '.epme-live-refresh-widget--keyup', epme_refresh_widget);
    });

    // Refresh the Widget
    function epme_refresh_widget() {
        epme_go_to(epme_get_current_step(), $new_widget);
    }

    // Filter Widget settings
    function epme_filter_preview_info(preview_info) {
        preview_info.position = 2;
        return preview_info;
    }

    // Fill widget's setting by actual information from form
    function epme_actual_information_from_form() {
        if (!epme_is_exist(window.preview_info.widget)) {
            window.preview_info.widget = {};
        }
        if (epme_is_exist(window.preview_info.cta_phrase)) {
            window.preview_info.widget.buttonText = window.preview_info.cta_phrase;
        }
        if (epme_is_exist(window.preview_info.channels)) {
            window.preview_info.widget.channels = window.preview_info.channels;
        }
        if (epme_is_exist(window.preview_info.guid)) {
            window.preview_info.widget.guid = window.preview_info.guid;
        }
        if (epme_is_exist(window.preview_info.id)) {
            window.preview_info.widget.id = window.preview_info.id;
        }
        if (epme_is_exist(window.preview_info.selector)) {
            window.preview_info.widget.selector = window.preview_info.selector;
        }
        if (epme_is_exist(window.preview_info.design)) {
            window.preview_info.widget.buttonDesign = window.preview_info.design;
        }
        if (epme_is_exist(window.preview_info.color)) {
            if (epme_is_exist(window.preview_info.color.back)) {
                window.preview_info.widget.buttonColor = window.preview_info.color.back;
            }
            if (epme_is_exist(window.preview_info.color.text)) {
                window.preview_info.widget.textColor = window.preview_info.color.text;
            }
        }
        if (epme_is_exist(window.preview_info.helping_text)) {
            window.preview_info.widget.saleText = window.preview_info.helping_text;
        }
        if (epme_is_exist(window.preview_info.position)) {
            window.preview_info.widget.position = window.preview_info.position;

            $new_widget.removeClass('epme-new-widget--d-1').removeClass('epme-new-widget--d-2');
            $new_widget.addClass('epme-new-widget--d-' + window.preview_info.position);
        }
        if (epme_is_exist(window.preview_info.textAlign)) {
            window.preview_info.widget.textAlign = window.preview_info.textAlign;
        }
    }

    // Init of preview information for current step
    function epme_preview_info_init() {
        try {
            var $widget_cont = $('.epme-new-widget__cont--' + epme_get_current_step()),
                $info_cont = $('.epme-preview-info', $widget_cont);

            if ($info_cont.length && $info_cont.text()) {
                return JSON.parse($info_cont.text());
            } else {
                console.error('Empty data for Preview');
                return false;
            }
        } catch (err) {
            epme_show_error(err);
        }
    }

    window.epme_preview_init = epme_preview_init;
    window.epme_preview_clear = epme_preview_clear;
    window.epme_preview_info_init = epme_preview_info_init;
    window.epme_preview_type_text = epme_preview_type_text;
    window.epme_actual_information_from_form = epme_actual_information_from_form;
    window.epme_create_widget = epme_create_widget;
    window.epme_preview_type_button = epme_preview_type_button;
    window.epme_filter_preview_info = epme_filter_preview_info;

}($ || window.jQuery));
// end of file