(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    /**
     * Requests to the server for Campaigns.
     * Create and edit messages for mailing.
     *
     * @param options
     * @constructor
     */
    function EPMECampaignsServer(options) {
        this.body = options.body || $('body');
        this.refresh = options.refresh || $('.epme-campaigns__refresh');
    }

    /**
     * Create and Update Campaign.
     *
     * @param data
     * @param $id
     */
    EPMECampaignsServer.prototype.create_campaign = function(data, $id) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_create_campaign',
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
    EPMECampaignsServer.prototype.delete_campaign = function(id) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_delete_campaign',
                nonce_code: epme_ajaxurl.nonce,
                id: id,
            },
            beforeSend: function () {
                epme_close_dialog_modal();
                epme_modal_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }

                this._respond = data.answer.respond;

                // if campaign deleted
                if (this._respond.id !== void 0) {
                    epme_modal_loading_stop();
                    epme_close_all_modal();
                    window.epme_campaigns_server.reload_campaigns(); // TODO Добавить окружение _this
                }

                if (data.message) {
                    epme_show_message(data.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_delete_campaign-@11: '+xhr.status);
                console.error('epme_delete_campaign-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_modal_loading_stop();
            }
        });
    };

    /**
     * Reload HTML table with Campaigns.
     */
    EPMECampaignsServer.prototype.reload_campaigns = function() {
        var $refresh = this.refresh;
        
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_reload_campaigns',
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

    /**
     * Reload HTML for campaign footer and testers.
     */
    EPMECampaignsServer.prototype.reload_campaign_footer = function($refresh, type_content) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_reload_campaign_footer',
                type: type_content,
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
                console.error('epme_reload_campaign_footer-@11: '+xhr.status);
                console.error('epme_reload_campaign_footer-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_loading_stop();
            }
        });
    };

    /**
     * Refresh filter information.
     *
     * @param  filters
     * @param  campaign_id
     * @param  _obj
     */
    EPMECampaignsServer.prototype.filters_done = function(filters, campaign_id, _obj) {
        var $count = $('.epme-filter-footer__total-subscribers');

        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_filters_done',
                filters: filters,
                campaign_id: campaign_id,
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                $count.html('<i class="epme-small-loader"></i>');
                epme_loading_start(1);
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }

                this._respond = data.answer.respond;
                if (this._respond.count !== void 0) {
                    $count.text(this._respond.count);
                    _obj.manage_button('next', 'enabled');
                    _obj.manage_button_jq($('.epme-message-build__send-prev'), 'enabled');
                }

                if (data.message) {
                    epme_show_message(data.message);
                }
            },
            complete: function () {
                epme_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_filters_done-@11: '+xhr.status);
                console.error('epme_filters_done-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
                epme_loading_stop();
            }
        });
    };

    /**
     * Send to preview.
     *
     * @param  content_type
     * @param  data
     */
    EPMECampaignsServer.prototype.send_to_preview = function(content_type, data) {
        $.ajax({
            type: 'POST',
            url: epme_ajaxurl.url,
            dataType: 'json',
            data: {
                action: 'epme_send_to_preview',
                content_type: content_type,
                data: data,
                nonce_code: epme_ajaxurl.nonce,
            },
            beforeSend: function () {
                epme_loading_start();
            },
            success: function (data) {
                if (epme_check_response(data)) {
                    return;
                }

                if (data.message) {
                    epme_loading_stop();
                    epme_show_message(data.message);
                }
            },
            complete: function () {
                epme_loading_stop();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error('epme_send_to_preview-@11: '+xhr.status);
                console.error('epme_send_to_preview-@12: '+thrownError);
                epme_show_error(thrownError + ', status: ' + xhr.status);
            }
        });
    };

    window.EPMECampaignsServer = EPMECampaignsServer;

}($ || window.jQuery));
// end of file