(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    /**
     * Form slider.
     *
     * @param  options
     * @constructor
     */
    function EPMECampaign(options) {
        var _this = this;

        this.body = options.body || $('body');
        this.global_contain = options.global_contain || $('.epme-cont');
        this.campaign_tab = options.campaign_tab || '.epme-tab-header--campaign-history';
        this.c = options.c || 'epme-wide-card';
        this.dc = '.' + this.c;
        this.contain = options.contain || $(this.dc, this.global_contain);
        this.server = options.server || window.epme_campaigns_server;
        // this.exception_cont = options.exception_cont || 'epme-wide-card__cont:not(.epme-wide-card__cont--fine-tune):not(.epme-wide-card__cont--master)';

        // Initial form
        this.initial_form();

        // Click to Navigation button (Next/Back)
        this.body.on('click', this.dc + '__nav', function () {
            var $this = $(this),
                this_nav = $this.data('nav');

            _this.go_to(this_nav);
        });

        // Input events check to require
        this.body.on('keyup', this.dc + ' .epme-required', {obj: _this}, EPMECampaign.prototype.input_events);
        this.body.on('change', this.dc + ' .epme-required', {obj: _this}, EPMECampaign.prototype.input_events);

        // Filters changed event
        this.body.on('change', this.dc + ' .epme-filter-select', {obj: _this}, EPMECampaign.prototype.filter_events);

        // Manage date
        this.body.on('epme.date-before', '.epme-campaigns__date', {obj: _this}, EPMECampaign.prototype.date_event);

        // Manage date
        this.body.on('click', '.epme-modal--delete-campaign .epme-modal__save', {obj: _this}, EPMECampaign.prototype.delete_campaign_event);

        // Click to tab Campaigns
        if ($(this.campaign_tab).length)
            this.body.on('click', this.campaign_tab, {obj: _this}, EPMECampaign.prototype.campaigns_tab_event);
    }

    /**
     * Steps functionality of the Campaign form.
     *
     * @param  old_step
     * @param  new_step
     */
    EPMECampaign.prototype.steps_functionality = function(old_step, new_step) {
        var step_name = this.get_type_of_step(old_step),
            new_step_name = this.get_type_of_step(new_step),
            $new_tab = $(this.dc + '__cont--' + new_step_name, this.contain),
            $id = $(this.dc + '__cont--name [name="epme-id"]'),
            data = {};

        switch (step_name) {
            case 'name':
                data = this.get_form_value('name', {
                    is_necessarily: false,
                });

                // If there are changes in the inputs
                if (epme_is_exist(data)) {
                    Object.assign(data, this.get_form_value('date', {}));
                    this.server.create_campaign(data, $id);
                }
                break;
        }

        switch (new_step_name) {
            case 'name':
                this.check_inputs($new_tab);
                break;
            case 'master':
                var $block = $('.epme-info--1', $new_tab),
                    $changed_channels = $('.epme-message-build__changed-channels', $block),
                    ch = window.epme_message_blocks.get_changed_channels();

                if (ch.length) {
                    $block.show();
                    $changed_channels.text(ch.toString());
                } else {
                    $block.hide();
                }
                this.check_inputs($new_tab);
                break;
            case 'filter':
                this.manage_button_jq($('.epme-message-build__send-prev'), 'disabled');
                this.manage_button('next', 'disabled');
                this.filters_done();

                break;
            case 'fine-tune':
                var $tabs = $('.epme-tabs', $new_tab),
                    $tab_first = $('.epme-tab:first-child', $tabs);

                $tab_first.trigger('click');
                this.check_inputs($new_tab);
                break;
            case 'finish':
                data = this.get_form_value('name', {
                    is_necessarily: true,
                });
                if (epme_is_exist(data)) {
                    Object.assign(data, this.get_form_value('date', {}));
                    Object.assign(data, {
                        'masterMessageBlock' : window.epme_message_blocks.get_master_blocks(),
                        'messageBlocks' : window.epme_message_blocks.get_message_blocks(),
                    });
                    this.server.create_campaign(data, $id);
                }
                break;
        }
    };

    /**
     * Return form value by step.
     *
     * @param  step
     * @param  options
     * @return object
     */
    EPMECampaign.prototype.get_form_value = function(step, options) {
        var $cont = $(this.dc + '__cont--' + step, this.contain),
            data = {},
            is_necessarily = options.is_necessarily || false;

        switch (step) {
            case 'name':
                var $name = $('[name="epme-name"]', $cont),
                    $note = $('[name="epme-note"]', $cont),
                    $id = $('[name="epme-id"]', $cont);

                data = {
                    name : $name.val(),
                    note : $note.val(),
                };

                if ($id.val()) {
                    if (!epme_is_value_changed($name) && !epme_is_value_changed($note) && !is_necessarily) {
                        return {};
                    }
                    Object.assign(data, {
                        id : $id.val(),
                    });
                }

                $name.data('prev-val', $name.val());
                $note.data('prev-val', $note.val());

                break;
            case 'date':
                var $date = $('[name="epme-date"]', $cont),
                    $time = $('[name="epme-time"]', $cont);

                data = {
                    date : $date.val(),
                    time : $time.val(),
                    time_zone : epme_get_current_time_zone(),
                };

                $date.data('prev-val', $date.val());
                $time.data('prev-val', $time.val());

                break;
        }
        return data;
    };

    /**
     * Return id of Campaign.
     *
     * @return int
     */
    EPMECampaign.prototype.get_id = function() {
        if (epme_is_exist(this._id)) {
            return this._id;
        } else {
            var $cont = $(this.dc + '__cont--name', this.contain),
                $id = $('[name="epme-id"]', $cont);

            if ($id.length && $id.val()) {
                this._id = parseInt($id.val());
                return this._id;
            } else {
                epme_show_error('Campaign ID is empty!');
                return 0;
            }
        }
    };

    /**
     * Setup form.
     */
    EPMECampaign.prototype.initial_form = function() {
        var current_step = this.get_current_step(),
            $cont = $(this.dc + '__cont', this.contain),
            $cont_target = $(this.dc + '__cont--'+current_step, this.contain);

        this.required_fields();
        if ($cont.length) {
            this.back_next_check_for_new_widget_form();

            $cont.hide();
            $cont_target.css({
                'display' : 'flex',
            });
        }
    };

    /**
     * Go to the N step.
     *
     * @param  new_step
     */
    EPMECampaign.prototype.go_to = function(new_step) {
        var current_step = this.get_current_step(),
            filtered_new_step = this.filter_nav_val(new_step);

        this.contain.removeClass(this.c + '--' + current_step).addClass(this.c + '--' + filtered_new_step);
        this.set_current_step(filtered_new_step);
        this.initial_form();

        this.required_fields();

        this.steps_functionality(current_step, filtered_new_step);
    };

    /**
     * Return current step.
     *
     * @return int
     */
    EPMECampaign.prototype.get_current_step = function() {
        if (!epme_is_exist(this.current_step)) {
            this.current_step = this.contain.data('step');
        }
        return this.current_step;
    };

    /**
     * Return type of current step.
     *
     * @param  step
     * @return string
     */
    EPMECampaign.prototype.get_type_of_step = function(step) {
        return ($(this.dc + '__cont--' + step, this.contain)).data('id');
    };

    /**
     * Set current step
     *
     * @param data
     */
    EPMECampaign.prototype.set_current_step = function(data) {
        this.current_step = data;
        this.contain.data('step', this.current_step);
    };

    /**
     * Return count of steps in the form
     * 
     * @return int
     */
    EPMECampaign.prototype.get_count_steps = function() {
        return $(this.dc + '__cont', this.contain).length * 1;
    };

    /**
     * Filter Next/Back value of step
     *
     * @param  data
     * @return int
     */
    EPMECampaign.prototype.filter_nav_val = function(data) {
        var new_step = data;

        switch (data) {
            case 'next' :
                new_step = this.get_current_step()*1 + 1;
                break;
            case 'back' :
                new_step = this.get_current_step()*1 - 1;
                break;
        }

        if (new_step < 0) {
            new_step = 0;
        }

        if (new_step > this.get_count_steps()) {
            new_step = this.get_count_steps();
        }

        return new_step;
    };

    /**
     * Hide/Show back/next/.. buttons
     */
    EPMECampaign.prototype.back_next_check_for_new_widget_form = function() {
        var current_step = this.get_current_step(),
            count_steps = this.get_count_steps();

        this.manage_button('back', 'show');
        this.manage_button('next', 'show');

        if ((current_step*1) === count_steps) {
            this.manage_button('save', 'show');
            this.manage_button('next', 'hide');
        } else {
            this.manage_button('save', 'hide');
        }

        if (current_step === count_steps) {
            this.manage_button('next', 'hide');
            this.manage_button('finish', 'show');
        } else {
            this.manage_button('finish', 'hide');
        }

        if (current_step === 1) {
            this.manage_button('back', 'hide');
        }
    };

    /**
     * Manage of button (hide, show, disabled, enabled).
     * 
     * @param  button
     * @param  action
     */
    EPMECampaign.prototype.manage_button = function(button, action) {
        var $button = $(this.dc + '__nav--' + button);

        if ($button.length) {
            this.manage_button_jq($button, action);
        }
    };

    /**
     * Manage of button by jQuery (hide, show, disabled, enabled).
     *
     * @param  $button
     * @param  action
     */
    EPMECampaign.prototype.manage_button_jq = function($button, action) {
        if ($button.length) {
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
                case 'enable' :
                    $button.prop('disabled', 0);
                    break;
            }
        }
    };

    /**
     * Check to the required fields
     */
    EPMECampaign.prototype.required_fields = function() {
        var _this = this,
            $cont = $(this.dc + '__cont--' + this.get_current_step()),
            $required_fields = $('.epme-required', $cont);

        if ($required_fields.length) {
            $required_fields.each(function () {
                var $this = $(this);

                if (!$this.val()) {
                    _this.manage_button('next', 'disabled');
                    return '';
                }
            });
        }
    };

    /**
     * Input events.
     */
    EPMECampaign.prototype.input_events = function(event) {
        if ($(this).val()) {
            event.data.obj.manage_button('next', 'enabled');
            event.data.obj.manage_button_jq($('.epme-message-build__send-prev'), 'enabled');
        } else {
            event.data.obj.manage_button('next', 'disabled');
            event.data.obj.manage_button_jq($('.epme-message-build__send-prev'), 'disabled');
        }
    };

    /**
     * Filters events.
     */
    EPMECampaign.prototype.filter_events = function(event) {
        if (!epme_is_exist(event.data.obj.filter_debounce))
            event.data.obj.filter_debounce = _.debounce(event.data.obj.filter_events_debounce, 600);

        event.data.obj.filter_debounce(event);
        event.data.obj.manage_button('next', 'disabled');
        event.data.obj.manage_button_jq($('.epme-message-build__send-prev'), 'disabled');
    };

    /**
     * Filters events with debounce.
     */
    EPMECampaign.prototype.filter_events_debounce = function(event) {
        event.data.obj.filters_done();
    };

    /**
     * Collection of filters and send to the server.
     */
    EPMECampaign.prototype.filters_done = function() {
        var $selects = $('.epme-filter-select', this.contain),
            filters = {},
            $item;

        for (var i = 0; i < $selects.length; i++) {
            $item = $($selects[i]);
            if ($($selects[i]).val()) {
                filters[$item.data('id')] = $($selects[i]).val();
            }
        }

        this.server.filters_done(filters, this.get_id(), this);
    };

    /**
     * Date event.
     */
    EPMECampaign.prototype.date_event = function() {
        var $this = $(this),
            text = $this.text(),
            date = new Date(text);
        
        if (date > new Date()) {
            $this.addClass('epme-date').addClass('epme-date--greatest');
        }
    };

    /**
     * Delete campaign click to "agree" in the modal.
     */
    EPMECampaign.prototype.delete_campaign_event = function(event) {
        event.data.obj.server.delete_campaign(window.epme_campaign_id);
        delete window.epme_campaign_id;
    };

    /**
     * Click to tab Campaigns.
     */
    EPMECampaign.prototype.campaigns_tab_event = function(event) {
        event.data.obj.server.reload_campaigns();
    };

    /**
     * Check empty inputs.
     */
    EPMECampaign.prototype.check_inputs = function($par) {
        var $inputs = $('.epme-required', $par),
            $button_to_prev = $('.epme-message-build__send-prev');
            _this = this;
        
        if (!$inputs.length) {
            _this.manage_button('next', 'disabled');
            _this.manage_button_jq($button_to_prev, 'disabled');
        } else {
            _this.manage_button_jq($button_to_prev, 'enabled');
            _this.manage_button('next', 'enabled');
            $inputs.each(function () {
                var $this = $(this);

                if (!$this.val()) {
                    _this.manage_button('next', 'disabled');
                    _this.manage_button_jq($button_to_prev, 'disabled');
                    return '';
                }
            });
        }
    };

    window.EPMECampaign = EPMECampaign;

}($ || window.jQuery));
// end of file