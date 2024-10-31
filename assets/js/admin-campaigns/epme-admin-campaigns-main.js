(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    window.epme_campaigns_server = new EPMECampaignsServer({});
    window.epme_campaign = new EPMECampaign({});
    window.epme_message_blocks = new EPMEMessageBlocks({});

    // Init blocks
    $(function () {
        var $master_blocks = $('#epme-master-blocks'),
            $fine_tune_blocks = $('#epme-fine-tune-blocks');

        if ($master_blocks.length && $fine_tune_blocks.length) {
            try {
                var master_blocks_json = JSON.parse($master_blocks.text()),
                    fine_tune_blocks_json = JSON.parse($fine_tune_blocks.text());

                window.epme_message_blocks.set_blocks_vars(master_blocks_json, []);
                window.epme_message_blocks.render_blocks('master');
                window.epme_message_blocks.set_blocks_vars([], fine_tune_blocks_json);
            } catch (err) {
                epme_show_error(err);
            }
        }
    });
}($ || window.jQuery));
// end of file