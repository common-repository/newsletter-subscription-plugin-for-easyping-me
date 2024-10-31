(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    var $body = $('body'),
        $epme_cont = $('.epme-cont');

    // Click to Change Plan
    $(function () {
        $body.on('click', '.epme-change-plan', function () {
            var $premium_tab = $('.epme-tab-header--premium-features span');

            $premium_tab.trigger('click');
        });
    });

    // Click to Email & password login option (show login form)
    $(function () {
        $body.on('click', '.epme-classic-login__link', function () {
            var $par = $('.epme-classic-login'),
                $int = $('.epme-classic-login__int', $par),
                $form = $('.epme-classic-login__form-box', $par);

            $int.slideUp();
            $form.slideDown();
        });
    });

    // Click to Show Token
    $(function () {
        $body.on('click', '.epme__show-token', function () {
            var $this = $(this),
                this_status = $this.data('status'),
                this_short = $this.data('short'),
                this_token = $this.data('token'),
                this_show = $this.data('show'),
                this_hide = $this.data('hide'),
                $parent = $this.closest('.epme-info-table__token-box'),
                $token = $('.epme-info-table__token', $parent);

            if (this_status === 'show') {
                $token.html(this_token);
                $this.data('status', 'hide');
                $this.html(this_hide);
            } else {
                $token.html(this_short);
                $this.data('status', 'show');
                $this.html(this_show);
            }
        });
    });

    // Click to Google Sign-in
    $(function () {
        $body.on('click', '.epme-sign-in', function () {
            var $this = $(this);

            $.ajax({
                type: 'POST',
                url: epme_ajaxurl.url,
                dataType: 'json',
                data: {
                    action: 'epme_sign_in',
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
                    if (data.html !== void 0 && data.html) {
                        $epme_cont.empty();
                        $epme_cont.append(data.html);
                        window.componentHandler.upgradeAllRegistered();
                    }
                    if (data.answer !== void 0 && data.answer) {
                        try {
                            if (data.answer.network === 'google') {
                                window.location.href = data.answer.url;
                            } else {
                                epme_loading_stop();
                            }
                        } catch (err) {
                            epme_loading_stop();
                            epme_show_error(err);
                        }
                    } else {
                        epme_loading_stop();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error('epme_sign_in-@11: '+xhr.status);
                    console.error('epme_sign_in-@12: '+thrownError);
                    epme_show_error(thrownError + ', status: ' + xhr.status);
                }
            });
        });
    });

    // Submit Classic Sign-in
    $(function () {
        $body.on('submit', 'form.epme-classic-login__form', function (e) {
            e = e || window.event;
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);

            var $this = $(this),
                $login = $('#epme-login', $this),
                $password = $('#epme-password', $this);

            if (!$login.val() || !$password.val()) {
                epme_show_warning(epme_texts('pl-empty-error'));
                return;
            }

            $.ajax({
                type: 'POST',
                url: epme_ajaxurl.url,
                dataType: 'json',
                data: {
                    action: 'epme_classic_sign_in',
                    nonce_code: epme_ajaxurl.nonce,
                    login: $login.val(),
                    password: $password.val(),
                },
                beforeSend: function () {
                    epme_loading_start();
                },
                success: function (data) {
                    if (epme_check_response(data)) {
                        epme_loading_stop();
                        return;
                    }
                    console.log(data);
                    if (data.html !== void 0 && data.html) {
                        $epme_cont.empty();
                        $epme_cont.append(data.html);
                        window.componentHandler.upgradeAllRegistered();
                    }
                },
                complete: function () {
                    epme_loading_stop();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error('epme_classic_sign_in-@11: '+xhr.status);
                    console.error('epme_classic_sign_in-@12: '+thrownError);
                    epme_show_error(thrownError + ', status: ' + xhr.status);
                }
            });
        });
    });

    // Click to Add Funds
    $(function () {
        $body.on('click', '.epme-add-funds', function () {
            var $this = $(this),
                $amount_payment__input = $('.amount-payment__input');

            if (!$amount_payment__input.val()) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: epme_ajaxurl.url,
                dataType: 'json',
                data: {
                    action: 'epme_add_funds',
                    nonce_code: epme_ajaxurl.nonce,
                    funds: $amount_payment__input.val(),
                },
                beforeSend: function () {
                    epme_modal_loading_start();
                },
                success: function (data) {
                    if (epme_check_response(data)) {
                        epme_modal_loading_stop();
                        return;
                    }
                    if (data.html !== void 0 && data.html) {
                        try {
                            $body.append(data.html);
                            var $paylane_form = $('.epme-paylane-form');

                            if ($paylane_form.length) {
                                $paylane_form.submit();
                            } else {
                                epme_show_error('Error: Paylane form is missed');
                                epme_modal_loading_stop();
                            }
                        } catch (err) {
                            epme_modal_loading_stop();
                            epme_show_error(err);
                        }
                    } else {
                        epme_modal_loading_stop();
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
        });
    });

    // Click to Sign-out
    $(function () {
        $body.on('click', '.epme-account__sign-out', function () {
            var $this = $(this);

            $.ajax({
                type: 'POST',
                url: epme_ajaxurl.url,
                dataType: 'json',
                data: {
                    action: 'epme_sign_out',
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
        });
    });

}($ || window.jQuery));
// end of file