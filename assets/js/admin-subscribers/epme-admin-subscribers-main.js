(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    window.epme_subscribers_server = new EPMESubscribersServer({});
    window.epme_testers = new EPMETesters({});
    // window.epme_list = new EPMEList({
    //     'server' : window.epme_subscribers_server,
    // });

}($ || window.jQuery));
// end of file