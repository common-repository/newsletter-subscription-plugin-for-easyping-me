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


    // Request to create new widget
    function epme_create_widget(respond, is_update_widget) {
        if (respond === void 0 || !respond) {
            respond = 0;
        }

        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_create_widget',
                nonce_code: epme_ajaxurl.nonce,
                respond: respond,
            },
            beforeSend: function () {
                epme_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }

                window.epme_respond = data.answer.respond;

                // if channel created
                if (window.epme_respond.guid !== void 0) {
                    if (!epme_is_exist(window.preview_info)) {
                        window.preview_info = {};
                    }
                    window.preview_info.guid = window.epme_respond.guid;
                    window.preview_info.id = window.epme_respond.id;

                    if (is_update_widget) {
                        epme_update_widget(window.preview_info);
                    }
                    epme_loading_stop();
                } else {
                    // if channel not a free
                    if (window.epme_respond.changeBalance !== void 0) {
                        if (window.epme_respond.changeBalance.amount*1 !== 0) {
                            if (window.epme_respond.changeBalance.complete === true) {
                                epme_on_change_balance_modal(window.epme_respond.changeBalance.amount*1, window.epme_respond.changeBalance.balance*1, 'epme-modal--create_new_widget');
                                epme_loading_stop();
                            }
                        }
                    }
                }

                if (data.message) {
                    epme_show_message(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_create_widget-@11: '+xhr.status);
                console.error('epme_create_widget-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_loading_stop();
            }
        });
    }

    // Update selected channels for current Widget
    function epme_update_widget(respond) {
        if (!epme_is_exist(respond) || !epme_is_exist(respond.id)) {
            epme_show_error('Empty id of Widget');
            return false;
        }

        if (!epme_is_exist(respond.progressive)) {
            epme_actual_information_from_form();
        }

        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_update_widget',
                respond: respond,
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }

                window.epme_respond = data.answer.respond;

                // if channel not a free
                if (window.epme_respond.changeBalance !== void 0) {
                    if (window.epme_respond.changeBalance.amount*1 !== 0) {
                        if (window.epme_respond.changeBalance.complete !== true) {
                            epme_on_change_balance_modal(window.epme_respond.changeBalance.amount*1, window.epme_respond.changeBalance.balance*1, 'epme-modal--update_widget');
                            epme_loading_stop();
                        }
                    }
                }

                if (data.message) {
                    epme_show_message(data.message);
                }
            },
            complete: function () {
                epme_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_update_widget-@11: '+xhr.status);
                console.error('epme_update_widget-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    }

    // Refresh Channels List
    $(function () {
        $body.on('click', '.epme-new-widget__refresh', function () {
            var $this = $(this),
                this_id_widget = $this.data('id-widget');

            $.ajax({
                type: 'POST',
                url: epme_ajaxurl.url,
                dataType: 'json',
                data: {
                    action: 'epme_widgets_list',
                    'id-widget': this_id_widget,
                    nonce_code: epme_ajaxurl.nonce,
                },
                beforeSend: function () {
                    epme_loading_start();
                },
                success: function (data) {
                    if (epme_check_response(data)) {
                        return;
                    }
                    if (data.html !== void 0 && data.html) {
                        var $list = $('.epme-channels-list__cont');

                        $list.empty();
                        $list.append(data.html);
                        window.componentHandler.upgradeAllRegistered();
                    }
                },
                complete: function () {
                    epme_loading_stop();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error('epme_widgets_list-@11: '+xhr.status);
                    console.error('epme_widgets_list-@12: '+thrownError);
                    epme_show_error(thrownError + ', status: ' + xhr.status);
                }
            });
        });
    });

    // Click to Delete in modal
    $(function () {
        $body.on('click', '.epme-modal--remove-widget .epme-modal__save', function () {
            epme_delete_widget(window.epme_widget_id);
            delete window.epme_widget_id;
        });
    });

    // Delete a widget by id
    function epme_delete_widget(id) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_delete_widget',
                id: id,
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_loading_start();
                epme_close_dialog_modal();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    epme_loading_stop();
                    return;
                }
                if (data.message) {
                    epme_show_message(data.message);
                    epme_loading_stop();
                    epme_refresh_widgets();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_delete_widget-@11: '+xhr.status);
                console.error('epme_delete_widget-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_loading_stop();
            }
        });
    }

    // Refresh Widget HTML
    function epme_refresh_widgets () {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_refresh_widgets',
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }
                if (data.html !== void 0 && data.html) {
                    var $cont = $('.mdl-tabs__panel--widgets');

                    if ($cont.length) {
                        $cont.empty();
                        $cont.append(data.html);
                        window.componentHandler.upgradeAllRegistered();
                    }
                }
            },
            complete: function () {
                epme_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_refresh_widgets-@11: '+xhr.status);
                console.error('epme_refresh_widgets-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    }

    // Click to Deactivate in modal
    $(function () {
        $body.on('click', '.epme-modal--deactivate-widget .epme-modal__save', function () {
            epme_active_widget({
                'id' : window.epme_widget_id,
                'active' : false,
            });
            console.log(123123);
            delete window.epme_widget_id;
        });
    });

    // Activate/Deactivate widget
    function epme_active_widget(respond) {
        if (!epme_is_exist(respond) || !epme_is_exist(respond.id)) {
            epme_show_error('Empty id of Widget');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_active_widget',
                respond: respond,
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_loading_start();
                epme_close_dialog_modal();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    epme_loading_stop();
                    return;
                }
                window.epme_respond = data.answer.respond;

                // if channel not a free
                if (window.epme_respond.changeBalance !== void 0) {
                    if (window.epme_respond.changeBalance.amount*1 !== 0 && window.epme_respond.changeBalance.complete !== true) {
                        epme_on_change_balance_modal(window.epme_respond.changeBalance.amount*1, window.epme_respond.changeBalance.balance*1, 'epme-modal--active_widget');
                        epme_loading_stop();
                    } else {
                        epme_refresh_widgets();
                    }
                } else {
                    epme_refresh_widgets();
                }

                if (data.message) {
                    epme_show_message(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_delete_widget-@11: '+xhr.status);
                console.error('epme_delete_widget-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_loading_stop();
            }
        });
    }

    window.epme_update_widget = epme_update_widget;
    window.epme_create_widget = epme_create_widget;
    window.epme_delete_widget = epme_delete_widget;
    window.epme_refresh_widgets = epme_refresh_widgets;
    window.epme_active_widget = epme_active_widget;

}($ || window.jQuery));
// end of file