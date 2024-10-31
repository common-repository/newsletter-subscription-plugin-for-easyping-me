(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    /**
     * Requests to the server for Subscribers.
     *
     * @param options
     * @constructor
     */
    function EPMESubscribersServer(options) {
        this.body = options.body || $('body');
        this.refresh = options.refresh || $('.epme-lists__refresh');
        this.testers_container = options.testers_container || $('.epme-testers');
    }

    /**
     * Create and Update Campaign.
     *
     * @param data
     * @param $id
     */
    EPMESubscribersServer.prototype.create_subscriber_list = function(data, $id) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_create_subscriber_list',
                nonce_code: epme_ajaxurl.nonce,
                data: data,
            },
            beforeSend: function () {
                epme_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }

                this._respond = data.answer.respond;

                // if campaign created
                if (epme_is_exist(this._respond) && epme_is_exist(this._respond.id)) {
                    $id.val(this._respond.id);
                    epme_loading_stop();
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
    };

    /**
     * Delete Campaign.
     *
     * @param id
     */
    EPMESubscribersServer.prototype.delete_tester = function(id) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_delete_tester',
                nonce_code: epme_ajaxurl.nonce,
                id: id,
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

                this._respond = data.answer.respond;

                // if tester deleted
                if (this._respond.id !== void 0) {
                    epme_loading_stop();
                    epme_close_all_modal();
                    window.epme_subscribers_server.reload_testers(); // TODO Добавить окружение _this
                }

                if (data.message) {
                    epme_show_message(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_delete_tester-@11: '+xhr.status);
                console.error('epme_delete_tester-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_loading_stop();
            }
        });
    };

    /**
     * Reload HTML table with Testers.
     */
    EPMESubscribersServer.prototype.reload_testers = function() {
        var $refresh = this.testers_container;

        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_refresh_testers',
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
                    if ($refresh.length) {
                        $refresh.empty();
                        $refresh.append(data.html);
                        window.componentHandler.upgradeAllRegistered();
                        epme_utc_time_to_local();
                        $('body').trigger('epme:testers-tab');
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
                console.error('epme_reload_campaign-@11: '+xhr.status);
                console.error('epme_reload_campaign-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_loading_stop();
            }
        });
    };

    window.EPMESubscribersServer = EPMESubscribersServer;

}($ || window.jQuery));
// end of file