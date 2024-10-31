(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme_cont = $('.epme-cont'),
        $new_widget = $('.epme-new-widget', $epme_cont);

    // Load form
    $(function () {
        epme_setup_new_widget_form($new_widget);
    });

    // Click to Navigation button (Next/Back)
    $(function () {
        $body.on('click', '.epme-new-widget__nav', function () {
            var $this = $(this),
                this_nav = $this.data('nav'),
                $new_widget_form = $this.closest('.epme-new-widget');

            epme_go_to(this_nav, $new_widget_form);
            epme_manage_button('next', 'enabled');
        });
    });

    // Color Picker
    $(function () {
        try {
            $(".epme-color-picker").each(function(i,e){
                window.epme_background_color = new MaterialColorPickerJS($(e)[0]);
            });
        } catch (err) {
            epme_show_error(err);
        }
    });

    // Select Channel on first step
    $(function () {
        $body.on('change', '.epme-channels-list__cont input.epme-channel-checkbox', function () {
            var $inputs = $('.epme-channels-list__cont input.epme-channel-checkbox:checked');

            if ($inputs.length === 0) {
                epme_manage_button('next', 'disabled');
            } else {
                epme_manage_button('next', 'enabled');
            }
        });
    });

    // Click to button Create (new widget)
    $(function () {
        $body.on('click', '.epme-widget__new-button', function () {
            var $new_widget_tab = $('.epme-tab-header--new-widget span');

            $new_widget_tab.trigger('click');
        });
    });

    // Click to tab on Widget Page
    $(function () {
        $body.on('click', '.epme-tab-header--widget-tab', function () {
            var $this = $(this),
                this_id = $this.data('id'),
                $new_widget_tab = $('.epme-tab-header--new-widget');

            switch (this_id) {
                case ('widgets') :
                    // $new_widget_tab.data('active', 0);
                    epme_refresh_widgets();
                    break;
                case ('new-widget') :
                    var new_widget_active = $new_widget_tab.data('active');

                    if (new_widget_active === void 0 || !new_widget_active) {
                        epme_create_widget();
                        $new_widget_tab.data('active', 1);
                    }
                    break;
            }
        });
    });

    // Click to buttons on Withdrawals form for Create New Widget
    $(function () {
        $body.on('click', '.epme-modal--create_new_widget .mdl-dialog__actions .mdl-button', function () {
            var $this = $(this);

            switch (true) {
                case ($this.hasClass('epme-modal__close')) :
                    var $tab_widgets = $('.epme-tab-header--widgets span'),
                        $new_widget_tab = $('.epme-tab-header--new-widget');

                    $new_widget_tab.data('active', 0);
                    $tab_widgets.trigger('click');
                    break;
                case ($this.hasClass('epme-modal__save')) :
                    epme_close_dialog_modal();
                    epme_create_widget(window.epme_respond);
            }
        });
    });

    // Click to buttons on Withdrawals form for Update Widget
    $(function () {
        $body.on('click', '.epme-modal--update_widget .mdl-dialog__actions .mdl-button', function () {
            var $this = $(this);

            switch (true) {
                case ($this.hasClass('epme-modal__close')) :
                    window.epme_respond.active = false;
                    window.epme_respond.progressive = true;
                    delete window.epme_respond.changeBalance;
                    delete window.epme_respond.status;

                    epme_update_widget(window.epme_respond);
                    break;
                case ($this.hasClass('epme-modal__save')) :
                    window.epme_respond.active = true;
                    window.epme_respond.progressive = true;
                    delete window.epme_respond.status;

                    epme_close_dialog_modal();
                    epme_update_widget(window.epme_respond);
            }
        });
    });

    // Click to buttons on Withdrawals form for Update Widget
    $(function () {
        $body.on('click', '.epme-modal--active_widget .mdl-dialog__actions .mdl-button', function () {
            var $this = $(this);

            switch (true) {
                case ($this.hasClass('epme-modal__close')) :
                    break;
                case ($this.hasClass('epme-modal__save')) :
                    delete window.epme_respond.status;

                    epme_close_dialog_modal();
                    epme_active_widget(window.epme_respond);
            }
        });
    });

    // Click to Toggle labels
    $(function () {
        $body.on('click', '.epme-toggle__text', function () {
            var $this = $(this),
                this_for = $this.data('for'),
                this_val = $this.data('val'),
                $input = $('#' + this_for);

            if ($input.length) {
                $input.prop('checked', this_val);
                $input.trigger('change');
            }
        });
    });

    // Change the Design Toggle
    $(function () {
        $body.on('change', '.epme-design-toggle', function () {
            var $this = $(this),
                $par = $this.closest('.epme-toggle'),
                $toggle__text = $('.epme-toggle__text', $par),
                $left = $('.epme-toggle__text--left', $par),
                $right = $('.epme-toggle__text--right', $par);

            $toggle__text.removeClass('epme-toggle__text--active');

            if ($this.prop('checked')) {
                $right.addClass('epme-toggle__text--active');
            } else {
                $left.addClass('epme-toggle__text--active');
            }
        });
    });

    // Change the Design textAlign
    $(function () {
        $body.on('click', '.epme-text-align__button', function () {
            var $this = $(this),
                this_id = $this.data('id'),
                $par = $this.closest('.epme-text-align'),
                $buttons = $('.epme-text-align__button', $par),
                $input_target = $('.epme-text-align__input--' + this_id, $par);

            $buttons.removeClass('mdl-button--raised').removeClass('mdl-button--primary').removeClass('mdl-button--colored');
            $this.addClass('mdl-button--raised').addClass('mdl-button--primary').addClass('mdl-button--colored');

            $input_target.prop('checked', true);
            $input_target.trigger('change');
        });
    });

    // Go to the N step
    function epme_go_to(new_step, $widget_form) {
        var this_nav = $widget_form.data('step'),
            new_stepme_filter = epme_filter_nav_val(new_step);

        if (!epme_is_exist(window.preview_info)) {
            epme_preview_init();
            return;
        }

        epme_stepme_functionality(this_nav, new_stepme_filter);
        $widget_form.removeClass('epme-new-widget--'+this_nav).addClass('epme-new-widget--'+new_stepme_filter);
        $widget_form.data('step', new_stepme_filter);
        epme_setup_new_widget_form($widget_form);
        epme_required_fields();
        epme_actual_information_from_form();
    }

    // Functionality of steps
    function epme_stepme_functionality(old_step, new_step) {
        var $cont = $('.epme-new-widget__cont--' + old_step),
            $cont_new = $('.epme-new-widget__cont--' + new_step),
            count_steps = epme_get_count_steps();

        switch ($cont.data('id')) {
            case 'channels':
                var $inputs = $('.epme-channels-list__cont input.epme-channel-checkbox:checked'),
                    $name = $('.epme-new-widget__name'),
                    selected_channels = [];

                if (window.preview_info === void 0) {
                    window.preview_info = {};
                }
                window.preview_info.channels = [];

                $inputs.each(function () {
                    var $this = $(this);

                    if ($this.hasClass('epme-channel-checkbox--mail')) {
                        window.preview_info.email = 1;
                        selected_channels.push('mail');
                    } else {
                        window.preview_info.channels.push({
                            'id' : $this.val(),
                            'name' : $this.data('name'),
                            'uri' : $this.data('uri'),
                            'network' : $this.data('network'),
                        });
                        selected_channels.push($this.val());
                    }
                });

                if ($name.length && $name.val()) {
                    window.preview_info.name = $name.val();
                }

                if (window.preview_info.init_channels === void 0) {
                    window.preview_info.init_channels = [];
                }

                if (epme_diff(window.preview_info.init_channels, selected_channels)) {
                    window.preview_info.init_channels = selected_channels;
                    if (!epme_is_exist(window.preview_info) || !epme_is_exist(window.preview_info.guid)) {
                        epme_create_widget(0, 1);
                    } else {
                        epme_update_widget(window.preview_info);
                    }
                }

                break;
            case 'cta_phrase':
                var $cta_phrase_input = $('.epme-cta-phrase__input');

                if ($cta_phrase_input.val()) {
                    window.preview_info.cta_phrase = $cta_phrase_input.val();
                }
                break;
            case 'design':
                var $design_toggle = $('.epme-design-toggle');

                if ($design_toggle.is(':checked')) {
                    window.preview_info.design = 1;
                } else {
                    window.preview_info.design = 2;
                }
                break;
            case 'color':
                var $color_back = $('.epme-color-picker--back'),
                    $color_text = $('.epme-color-picker--text');

                window.preview_info.color = {
                    'back': $color_back.val(),
                    'text': $color_text.val(),
                };
                break;
            case 'helping_text':
                var $helping_text = $('.epme-helping-text__input'),
                    helping_text = $helping_text.val();

                if (!helping_text) {
                    helping_text = $helping_text.attr('placeholder');
                }
                window.preview_info.helping_text = helping_text;
                break;
            case 'button_position':
                var $position = $('[name="epme-button-position__input"]:checked'),
                    $align = $('[name="epme-text-align"]:checked');

                window.preview_info.position = $position.val()*1;
                window.preview_info.textAlign = $align.val();
                break;
        }

        switch ($cont_new.data('id')) {
            case 'helping_text':
                epme_open_widget();
                break;
            // case 'button_position':
            //     var $position_input = $('.epme-button-position__input--' + window.preview_info.position);
            //
            //     if ($position_input.length) {
            //         console.log($position_input);
            //         $position_input.trigger('click');
            //     }
            //     break;
            case 'finish':
                var $code = $('.epme-widget-finish__code');

                if (epme_is_exist(window.preview_info.id)) {
                    $code.each(function () {
                        var $this = $(this);

                        $this.text($this.text().replace(/123/g, window.preview_info.id));
                    });
                }

                break;
        }

        if (new_step === count_steps) {
            window.preview_info.widget.active = true;
            epme_update_widget(window.preview_info);
        }
    }

    // Check to the required fields
    function epme_required_fields() {
        var $cont = $('.epme-new-widget__cont--' + epme_get_current_step()),
            $required_fields = $('.epme-required', $cont);

        if ($required_fields.length) {
            var promise = new Promise(function (resolve, reject) {
                $required_fields.each(function () {
                    var $this = $(this);

                    if (!$this.val()) {
                        resolve();
                    }
                });
            });
            promise
                .then(
                    function () {
                        epme_manage_button('next', 'disabled');
                    }
                );
        }
    }

    // Change the Design Toggle
    $(function () {
        $body.on('keyup', '.epme-required', function () {
            if ($(this).val()) {
                epme_manage_button('next', 'enabled');
            } else {
                epme_manage_button('next', 'disabled');
            }
        });
        $body.on('change', '.epme-required', function () {
            if ($(this).val()) {
                epme_manage_button('next', 'enabled');
            } else {
                epme_manage_button('next', 'disabled');
            }
        });
    });

    // Setup new widget form
    function epme_setup_new_widget_form($this) {
        var this_step = $this.data('step'),
            $epme_new_widget__cont = $('.epme-new-widget__cont', $this),
            $epme_new_widget__cont_target = $('.epme-new-widget__cont--'+this_step, $this);

        if ($epme_new_widget__cont.length) {
            epme_back_next_check_for_new_widget_form($this);
            epme_preview_init();

            $epme_new_widget__cont.hide();
            $epme_new_widget__cont_target.css({
                'display' : 'flex',
            });
        }
    }

    // Hide/Show back/next buttons
    function epme_back_next_check_for_new_widget_form($this) {
        var this_step = $this.data('step'),
            count_steps = epme_get_count_steps(),
            $button__finish = $('.epme-new-widget__nav--finish', $this);

        epme_manage_button('back', 'show');
        epme_manage_button('next', 'show');

        if ((this_step*1 + 1) === count_steps) {
            epme_manage_button('save', 'show');
            epme_manage_button('next', 'hide');
        } else {
            epme_manage_button('save', 'hide');
        }

        if (this_step === count_steps) {
            epme_manage_button('next', 'hide');
            $button__finish.show();
        } else {
            $button__finish.hide();
        }

        if (this_step === 1) {
            epme_manage_button('back', 'hide');
        }
    }

    // Filter Next/Back value of step
    function epme_filter_nav_val(data) {
        var new_step = data;

        switch (data) {
            case 'next' :
                new_step = epme_get_current_step()*1 + 1;
                break;
            case 'back' :
                new_step = epme_get_current_step()*1 - 1;
                break;
        }

        if (new_step < 0) {
            new_step = 0;
        }

        if (new_step > epme_get_count_steps()) {
            new_step = epme_get_count_steps();
        }

        return new_step;
    }

    function epme_get_count_steps() {
        return $('.epme-new-widget__cont', $new_widget).length;
    }

    function epme_get_current_step() {
        return $new_widget.data('step');
    }

    function epme_set_current_step(data) {
        $new_widget.data('step', data*1);
    }

    function epme_manage_button(button, action) {
        var $button;
        switch (button) {
            case 'next' :
                $button = $('.epme-new-widget__nav--next');
                break;
            case 'back' :
                $button = $('.epme-new-widget__nav--back');
                break;
            case 'save' :
                $button = $('.epme-new-widget__nav--save');
                break;
            default :
                $button = $(button);
                break;
        }

        switch (action) {
            case 'hide' :
                $button.hide();
                break;
            case 'show' :
                $button.show();
                break;
            case 'disabled' :
                $button.prop('disabled', 1);
                break;
            case 'enabled' :
                $button.prop('disabled', 0);
                break;
        }
    }

    // Open Widget
    function epme_open_widget() {
        var $cont_button = $('.epme-prev__cont--button'),
            $button = $('button.main', $cont_button);
    }

    window.epme_setup_new_widget_form = epme_setup_new_widget_form;
    window.epme_back_next_check_for_new_widget_form = epme_back_next_check_for_new_widget_form;
    window.epme_filter_nav_val = epme_filter_nav_val;
    window.epme_stepme_functionality = epme_stepme_functionality;
    window.epme_get_count_steps = epme_get_count_steps;
    window.epme_get_current_step = epme_get_current_step;
    window.epme_set_current_step = epme_set_current_step;
    window.epme_manage_button = epme_manage_button;
    window.epme_go_to = epme_go_to;
    window.epme_open_widget = epme_open_widget;

}($ || window.jQuery));
// end of file