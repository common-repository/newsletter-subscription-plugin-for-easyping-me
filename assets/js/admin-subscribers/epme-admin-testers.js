(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    /**
     * Testers.
     *
     * @param  options
     * @constructor
     */
    function EPMETesters(options) {
        var _this = this;

        this.body = options.body || $('body');
        this.global_contain = options.global_contain || $('.epme-cont');

        this.testers_tab = options.testers_tab || '.epme-tab-header--testers';

        this.server = options.server || window.epme_subscribers_server;

        // Manage date
        this.body.on('click', '.epme-modal--delete-tester .epme-modal__save', {obj: _this}, EPMECampaign.prototype.delete_tester_event);

        // Click to tab Testers
        if ($(this.testers_tab).length)
            this.body.on('click', this.testers_tab, {obj: _this}, EPMECampaign.prototype.testers_tab_event);
    }

    /**
     * Click to tab Testers.
     */
    EPMECampaign.prototype.testers_tab_event = function(event) {
        event.data.obj.server.reload_testers();
    };

    /**
     * Delete tester click to "agree" in the modal.
     */
    EPMECampaign.prototype.delete_tester_event = function(event) {
        event.data.obj.server.delete_tester(window.epme_tester_id);
        delete window.epme_tester_id;
    };

    window.EPMETesters = EPMETesters;

}($ || window.jQuery));
// end of file