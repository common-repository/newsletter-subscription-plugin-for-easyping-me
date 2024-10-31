(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme_cont = $('.epme-cont');

    // Click to Save in channel modal
    $(function () {
        $body.on('click', '.epme-channels__save-channel', function () {
            var $this = $(this),
                $epme_modal = $this.closest('.epme-modal'),
                $form = $('.epme-chl-edit-table__form', $epme_modal),
                $token_input = $('[name="token"]', $form);

            if (!$token_input.length || $token_input.val()) {
                $form.submit();
            } else {
                epme_show_error('Error: Empty Token');
            }
        });
    });

    // Agree to withdrawals
    $(function () {
        $body.on('click', '.epme-modal--withdrawals .epme-modal__save', function () {
            epme_close_dialog_modal();
            epme_channel_save();
        });
    });

    // Channel Save form submit
    $(function () {
        $body.on('submit', 'form.epme-chl-edit-table__form', function (e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);

            var $this = $(this),
                form_data = $this.serialize(),
                channel_id = $('[name="id"]', $this).val(),
                this_prefix = $this.data('prefix');

            if (this_prefix === 'vk') {
                var $radio_checked = $('[type="radio"]:checked', $this),
                    link = $radio_checked.val();

                epme_modal_loading_start();
                epme_delete_oauth_progress(link);
                return;
            }

            $.ajax({
                type: 'POST',
                url: epme_ajaxurl.url,
                dataType: 'json',
                async: true,
                data: form_data,
                beforeSend: function (xhr, ajaxOptions, thrownError) {
                    epme_modal_loading_start();
                },
                success: function (data) {
                    try {
                        if (epme_check_response(data)) {
                            window.epme_is_refresh_channels = true;
                            epme_modal_loading_stop();
                            return;
                        }

                        if (!channel_id || channel_id === 'empty') {
                            window.respond = data.answer.respond;

                            // if channel created
                            if (window.respond.active !== void 0) {
                                epme_modal_loading_stop();
                                epme_close_all_modal();
                                epme_refresh_channels(false);
                            } else {
                                // if channel not a free
                                if (window.respond.changeBalance !== void 0) {
                                    if (window.respond.changeBalance.amount*1 !== 0) {
                                        epme_on_change_balance_modal(window.respond.changeBalance.amount*1, window.respond.changeBalance.balance*1, 'epme-modal--withdrawals');
                                    } else {
                                        epme_channel_save();
                                    }
                                }
                            }
                        } else {
                            epme_modal_loading_stop();
                        }

                        window.epme_is_refresh_channels = true;

                        if (data.message) {
                            epme_show_message(data.message);
                        }
                    } catch (err) {
                        epme_modal_loading_stop();
                        epme_show_error(err);
                    }
                },
                complete: function (xhr, ajaxOptions, thrownError) {
                    // epme_modal_loading_stop();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error('epme-channel__form-@11: '+xhr.status);
                    console.error('epme-channel__form-@12: '+thrownError);
                    epme_show_error(thrownError + ', status: ' + xhr.status);
                }
            });
        });
    });

    function epme_channel_save() {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_channel_save_with_agree',
                respond: window.respond,
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_modal_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    epme_modal_loading_stop();
                    return;
                }
                if (data.message) {
                    epme_show_message(data.message);
                }
                window.epme_is_refresh_channels = true;
                delete window.respond;
            },
            complete: function () {
                epme_modal_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_sign_in-@11: '+xhr.status);
                console.error('epme_sign_in-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    }

    // Refresh HTML for Channels
    function epme_refresh_channels(is_on_refresh) {
        if (is_on_refresh && !window.epme_is_refresh_channels) {
            return;
        }
        window.epme_is_refresh_channels = false;

        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_refresh_channels',
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    epme_loading_stop();
                    return;
                }
                if (data.html) {
                    $epme_cont.empty();
                    $epme_cont.append(data.html);
                    window.componentHandler.upgradeAllRegistered();
                }
            },
            complete: function () {
                epme_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_sign_in-@11: '+xhr.status);
                console.error('epme_sign_in-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    }

    // Choose Page radio btn
    $(function () {
        $body.on('change', '.epme-group-select__radio input[type="radio"]', function () {
            var $this = $(this);

            epme_enable_save_btn($this, false);
        });
    });

    // Enable/Disable Save btn on change in text inputs
    $(function () {
        $body.on('keyup', '.epme-modal input[type="text"]', function () {
            var $this = $(this);

            if (epme_check_form_on_empty_fields($this)) {
                epme_enable_save_btn($this, false);
            } else {
                epme_enable_save_btn($this, true);
            }
        });
    });

    // Enable/Disable Save btn on change in checkboxes
    $(function () {
        $body.on('change', '.epme-modal input[type="checkbox"]', function () {
            var $this = $(this);

            if (epme_check_form_on_empty_fields($this)) {
                epme_enable_save_btn($this, false);
            } else {
                epme_enable_save_btn($this, true);
            }
        });
    });

    // Agree to withdrawals on Authorize
    $(function () {
        $body.on('click', '.epme-modal--authorize .epme-modal__save', function () {
            epme_open_link();
        });
    });

    // Click to cancel for Oauth (processed) form
    $(function () {
        $body.on('click', '.epme-modal--processed .mdl-dialog__actions .epme-modal__close', function () {
            epme_delete_oauth_progress('');
        });
    });

    // Authorize Twitter, VK, FB
    $(function () {
        $body.on('click', '.epme-button-form__button', function () {
            var $this = $(this),
                this_prefix = $this.data('prefix'),
                this_link = $this.data('link');

            if (this_link === void 0 || !this_link) {
                epme_channel_with_button_authorize(this_prefix, true);
            } else {
                epme_open_link(this_link);
            }
        });
    });

    // Authorize Twitter, VK, FB (first stage)
    function epme_channel_with_button_authorize(this_prefix, is_go_to_link) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_authorize',
                prefix: this_prefix,
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_modal_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    epme_modal_loading_stop();
                    return;
                }

                var respond = data.answer.respond;
                window.answer_url = respond.url;

                if (respond.changeBalance !== void 0 && respond.changeBalance.amount*1 !== 0) {
                    epme_on_change_balance_modal(respond.changeBalance.amount*1, respond.changeBalance.balance*1, 'epme-modal--authorize');
                } else {
                    if (respond.url !== void 0) {
                        if (is_go_to_link === true) {
                            epme_open_link(respond.url);
                        } else {
                            var $modal = $('.epme-modal--empty.epme-modal--'+this_prefix),
                                $btn = $('.epme-button-form__button', $modal);

                            $btn.data('link', respond.url);
                        }
                    }
                }
            },
            complete: function () {
                // epme_modal_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_sign_in-@11: '+xhr.status);
                console.error('epme_sign_in-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    }

    function epme_delete_oauth_progress(link) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_delete_oauth_progress',
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_loading_start();
            },
            success: function (data) {
                if (link) {
                    epme_open_link(link);
                }
                epme_check_response(data);
            },
            complete: function () {
                epme_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_delete_oauth_progress-@11: '+xhr.status);
                console.error('epme_delete_oauth_progress-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    }

    function epme_open_link(link) {
        if (link) {
            window.location.href = link;
            // window.open(link, '_blank').focus();
        } else {
            window.location.href = window.answer_url;
            // window.open(window.answer_url, '_blank').focus();
        }
    }

    function epme_enable_save_btn($elem, is_disable) {
        var $modal = $elem.closest('.epme-modal'),
            $save_btn = $('.epme-channels__save-channel', $modal);

        $save_btn.prop('disabled', is_disable);
    }

    function epme_check_form_on_empty_fields($elem) {
        var $modal = $elem.closest('.epme-modal'),
            $inputs = $('.epme-textfield__input--required', $modal);

        window.epme_flag = true;

        $inputs.each(function () {
            var $this = $(this);
            if (!$this.val().length) {
                window.epme_flag = false;
            }
        });

        var epme_flag = window.epme_flag;
        delete window.epme_flag;
        return epme_flag;
    }

    window.epme_channel_save = epme_channel_save;
    window.epme_refresh_channels = epme_refresh_channels;
    window.epme_channel_with_button_authorize = epme_channel_with_button_authorize;
    window.epme_open_link = epme_open_link;
    window.epme_delete_oauth_progress = epme_delete_oauth_progress;
    window.epme_enable_save_btn = epme_enable_save_btn;
    window.epme_check_form_on_empty_fields = epme_check_form_on_empty_fields;

}($ || window.jQuery));
// end of file