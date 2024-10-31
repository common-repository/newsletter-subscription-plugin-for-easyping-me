(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme_cont = $('.epme-cont');

    // Edit form submit
    $(function () {
        $body.on('submit', 'form.epme-edit__form', function (e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);

            var $this = $(this),
                form_data = $this.serialize(),
                $required_inputs = $('.epme-required', $this);

            for (var i = 0; i < $required_inputs.length; i++) {
                if (!$($required_inputs[i]).val()) {
                    return '';
                }
            }

            $.ajax({
                type: 'POST',
                url: epme_ajaxurl.url,
                dataType: 'json',
                async: true,
                data: form_data,
                beforeSend: function (xhr, ajaxOptions, thrownError) {
                    epme_loading_start();
                },
                success: function (data) {
                    try {
                        if (epme_check_response(data)) {
                            epme_loading_stop();
                            return;
                        }
                        if (data.type !== void 0 && data.type) {
                            switch (data.type) {
                                case 'change_name':
                                    epme_change_name(data);
                                    break;
                                case 'change_country':
                                    epme_change_country(data);
                                    break;
                            }
                        }
                        if (data.message) {
                            epme_show_message(data.message);
                        }
                    } catch (err) {
                        epme_loading_stop();
                        epme_show_error(err);
                    }
                },
                complete: function (xhr, ajaxOptions, thrownError) {
                    epme_loading_stop();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error('epme-edit__form-@11: '+xhr.status);
                    console.error('epme-edit__form-@12: '+thrownError);
                    epme_show_error(thrownError + ', status: ' + xhr.status);
                }
            });
        });
    });

    // Edit buttons
    $(function () {
        $body.on('click', '.epme-edit-btn', function () {
            var $this = $(this),
                this_target = $this.data('target'),
                this_edit = $this.data('edit'),
                this_save = $this.data('save'),
                $edit_cont = $('.epme-edit--'+this_target);

            // Is not Save button (Edit)
            if (!$this.hasClass('epme-edit-btn--save')) {

                $edit_cont.removeClass('epme-edit--default').addClass('epme-edit--to');
                $this.removeClass('epme-edit');
                $this.html(this_save);

                $this.addClass('epme-edit-btn--save').addClass('mdl-button--raised').addClass('mdl-button--colored');
            } else {
                var $form = $('form.epme-edit__form', $edit_cont);

                $edit_cont.addClass('epme-edit--default').removeClass('epme-edit--to');
                $this.addClass('epme-edit');
                $this.html(this_edit);
                $this.removeClass('epme-edit-btn--save').removeClass('mdl-button--raised').removeClass('mdl-button--colored');

                if(epme_is_change($this)) {
                    $form.submit();
                }
            }
        });
    });

    // Focus beauty input
    $(function () {
        $body.on('focus', '.epme-textfield__input', function () {
            var $this = $(this),
                $parent = $this.closest('.epme-textfield');

            $parent.addClass('epme-textfield--focused');
        });
        $body.on('focusout', '.epme-textfield__input', function () {
            var $this = $(this),
                $parent = $this.closest('.epme-textfield');

            $parent.removeClass('epme-textfield--focused');
        });
    });

    function epme_change_name(data) {
        var $default = $('.epme-edit--project .epme-edit__default'),
            $to = $('.epme-edit--project .epme-edit__to .epme-textfield__input');

        try {
            if (data.new_name.project) {
                $default.html(data.new_name.project);
                $to.val(data.new_name.project);
            }
        } catch (err) {
            epme_loading_stop();
            epme_show_error(err);
        }
    }

    function epme_change_country(data) {
        var $default = $('.epme-edit--country .epme-edit__default'),
            $to = $('.epme-edit--country .epme-edit__to .epme-textfield__select');

        try {
            if (data.country && data.val) {
                $default.data('val', data.val);
                $default.html(data.country);
                $to.val(data.val);
            }
        } catch (err) {
            epme_loading_stop();
            epme_show_error(err);
        }
    }

    function epme_is_change($this) {
        var this_type = $this.data('type'),
            this_target = $this.data('target'),
            this_default = $this.data('default'),
            $edit_cont = $('.epme-edit--'+this_target),
            $default = $('.epme-edit__default', $edit_cont),
            $to;

        switch (this_type) {
            case 'text':
                $to = $('.epme-edit__to .epme-textfield__input', $edit_cont);
                return ($default.html() !== $to.val());
            case 'select':
                var default_val = $default.data('val');

                $to = $('.epme-edit__to .epme-textfield__select', $edit_cont);
                return (default_val !== $to.val());
            default:
                return true;
        }
    }
    window.epme_change_name = epme_change_name;
    window.epme_change_country = epme_change_country;
    window.epme_is_change = epme_is_change;

}($ || window.jQuery));
// end of file