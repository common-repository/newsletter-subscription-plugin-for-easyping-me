(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    /**
     * Form slider.
     * List of Subscribers.
     *
     * @param  options
     * @constructor
     */
    function EPMEList(options) {
        EPMECampaign.apply(this, arguments);
    }

    EPMEList.prototype = Object.create(EPMECampaign.prototype);
    EPMEList.prototype.constructor = EPMEList;


    /**
     * Steps functionality of the Subscribers list form.
     *
     * @param  old_step
     * @param  new_step
     */
    EPMEList.prototype.steps_functionality = function(old_step, new_step) {
        var step_name = this.get_type_of_step(old_step),
            new_step_name = this.get_type_of_step(new_step),
            $id = $(this.dc + '__cont--name [name="epme-id"]'),
            data = {};

        switch (step_name) {
            case 'name':
                break;
        }

        switch (new_step_name) {
            case 'finish':
                data = this.get_form_value('name', {
                    is_necessarily: true,
                });
                //todo добавить в data информацию о выбранных фильтрах
                if (epme_is_exist(data)) {
                    this.get_filters_value(); return;
                    this.server.create_subscriber_list(data, $id);
                }
                break;
        }
    };

    /**
     * Return value of filters.
     *
     * @return object
     */
    EPMECampaign.prototype.get_filters_value = function() {
        var $cont = $(this.dc + '__cont--filter', this.contain),
            data = {},
            $selects = $('.epme-filter-select', $cont);

        console.log($selects);

        return data;
    };

    window.EPMEList = EPMEList;

}($ || window.jQuery));
// end of file