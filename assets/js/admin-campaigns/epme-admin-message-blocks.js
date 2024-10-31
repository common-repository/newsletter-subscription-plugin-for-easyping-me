(function ($) {
    if (!$) {
        console.error('jQuery and $ are missing');
        return;
    }

    /**
     * Message blocks.
     * Create and edit messages for mailing.
     *
     * @param options
     * @constructor
     */
    function EPMEMessageBlocks(options) {
        var _this = this;

        this.body = options.body || $('body');
        this.server = options.server || window.epme_campaigns_server;
        this.global_contain = options.global_contain || $('.epme-cont');
        this.c = options.c || 'epme-wide-card__cont';
        this.dc = '.' + this.c;
        this.k = options.k || 'epme-new-campaign';
        this.dk = '.' + this.k;
        this.contain = options.contain || $(this.dk, this.global_contain);

        this.sb = options.sb || 'epme-source-message-block';
        this.dsb = '.' + this.sb;

        this.build = options.build || 'epme-message-build';
        this.dbuild = '.' + this.build;

        this.mbs = options.mbs || 'epme-message-blocks';
        this.dmbs = '.' + this.mbs;

        this.mb = options.mb || 'epme-message-block';
        this.dmb = '.' + this.mb;

        this.cm = options.cm || 'empe-campaign-message';
        this.dcm = '.' + this.cm;

        this.sortable_cont = options.sortable_cont || $('.empy-sortable', this.contain);

        /** {
            "blockType": "image",
            "content": "<content>",
            "order": number,
        } */
        this._masterMessageBlock = options.masterMessageBlock || [];

        /** {
            ... like _masterMessageBlock,
            "socialNetworkType": "fb",
        } */
        this._messageBlocks = options.messageBlocks || [];

        // Init block's events.
        // Click to Add button (add new block)
        // Change inputs with content
        // Click to Delete block
        // Click to Send to Preview
        // Click to Refresh testers
        this.initial_blocks_events();

        // Init fine-tune tabs events
        this.initial_tabs_events();

        // Init drag and drop
        this.initial_drag_and_drop();
    }

    /**
     * Init drag and drop blocks.
     */
    EPMEMessageBlocks.prototype.initial_drag_and_drop = function() {
        var _this = this;

        this.sortable_cont.sortable({
            placeholder: "epme-state-highlight",
            start: function(event, ui) {
                ui.placeholder.height(ui.helper.height());
            },
            stop: (function(self){
                return function(event, ui) {
                    var $this_block = ui.item,
                        $par = $this_block.closest(self.dmbs),
                        $blocks = $(self.dmb, $par),
                        old_order = $this_block.data('order'),
                        content_type = $this_block.data('content-type'),
                        class_of_block = self.mb + '--' + old_order,
                        this_id = self.get_id(content_type, old_order),
                        id;

                    // find position of target (this) Block
                    var promise = new Promise(function (resolve) {
                        $blocks.each(function (i) {
                            var $this = $(this);

                            if ($this.hasClass(class_of_block)) {
                                resolve(i);
                            }
                        });
                    });

                    promise.then(function (position) {
                        var new_order = position*1 + 1;

                        if (new_order < old_order) {
                            for (var i = (old_order - 1); i >= new_order; i--) {
                                id = self.get_id(content_type, i);
                                switch (content_type) {
                                    case 'master':
                                        self._masterMessageBlock[id].order = i + 1;
                                        break;
                                    default:
                                        self._messageBlocks[id].order = i + 1;
                                }
                            }
                        }

                        if (new_order > old_order) {
                            for (var j = (old_order + 1); j <= new_order; j++) {
                                id = self.get_id(content_type, j);
                                switch (content_type) {
                                    case 'master':
                                        self._masterMessageBlock[id].order = j - 1;
                                        break;
                                    default:
                                        self._messageBlocks[id].order = j - 1;
                                }
                            }
                        }

                        // Change target (this) Block
                        switch (content_type) {
                            case 'master':
                                self._masterMessageBlock[this_id].order = new_order;
                                break;
                            default:
                                self._messageBlocks[this_id].order = new_order;
                        }
                        self.render_blocks(content_type);
                    });
                };
            })(_this),
        }).disableSelection();
    };

    /**
     * Init block's events.
     */
    EPMEMessageBlocks.prototype.initial_blocks_events = function() {
        var _this = this;

        // Click to Add (add new block)
        this.body.on('click', this.dsb + '__add', {obj: _this}, function (event) {
            event.data.obj.add_button_click($(this), true);
        });

        // Click to Add block in the modal
        this.body.on('click', '.epme-modal--block-change-button .epme-modal__save', {obj: _this}, function (event) {
            epme_close_dialog_modal();
            $('.epme-info--1').hide();
            event.data.obj.add_block_by_button(_this._add_button, true);
        });

        // Change inputs with content
        var input_content = this.dc + '--master .epme-required, ' + this.dc + '--fine-tune .epme-required';

        this.body.on('keyup', input_content, {obj: _this}, function (event) {
            event.data.obj.block_input_change($(this));
        });
        this.body.on('change', input_content, {obj: _this}, function (event) {
            event.data.obj.block_input_change($(this));
        });

        // Change inputs with content in the Master
        this.body.on('change', this.dcm + '--master .epme-block-input--change', {obj: _this}, function (event) {
            event.data.obj._messageBlocks = [];
        });

        // Focus inputs with content in the Master
        this.body.on('focus', this.dcm + '--master .epme-block-input--change', {obj: _this}, function (event) {
            event.data.obj.block_input_warning('epme-modal--block-change');
        });

        // Focus inputs with content
        this.body.on('click', '.epme-modal--block-change .epme-modal__save', {obj: _this}, function (event) {
            epme_close_dialog_modal();
        });

        // Click to Delete block
        this.body.on('click', '.epme-message-block__delete', {obj: _this}, function (event) {
            event.data.obj.block_delete_handler($(this));
        });

        // Click to Send to Preview
        this.body.on('click', '.epme-message-build__send-prev', {obj: _this}, function (event) {
            event.data.obj.send_to_preview_handler($(this));
        });

        // Click to Refresh testers
        this.body.on('click', '.epme-message-build__testers-refresh', {obj: _this}, function (event) {
            event.data.obj.reload_campaign_footer_handler($(this));
        });
    };

    /**
     * Init warning interface on change right part.
     */
    EPMEMessageBlocks.prototype.block_input_warning = function(class_name) {
        if (this.is_changed_warning()) {
            // epme_open_dialog_modal(epme_texts('mb-content'), epme_texts('mb-title'), epme_texts('mb-understand'), epme_texts('mb-close'), class_name);
            epme_show_warning({
                message: epme_texts('mb-title') + ' ' + epme_texts('mb-content'),
                timeout: 3000,
            });
        }
    };

    /**
     * Is channel have changes?
     */
    EPMEMessageBlocks.prototype.is_changed_warning = function() {
        return !!(this.get_changed_channels().length);
    };

    /**
     * Init message block interface.
     */
    EPMEMessageBlocks.prototype.block_input_change = function($this) {
        var $block = $this.closest(this.dmb),
            $par = $this.closest(this.dmbs),
            this_content_type = $block.data('content-type'),
            this_order = $block.data('order'),
            extra_content = '';

        if (epme_is_exist($this.data('extra-content'))) {
            extra_content = $this.data('extra-content');
        }

        this.set_block_value(this_content_type, this_order, $this.val(), extra_content);
        this.check_inputs($par, this_content_type, 1);
    };

    /**
     * Init message block interface.
     */
    EPMEMessageBlocks.prototype.check_inputs = function($par, content_type, delay) {
        if (content_type !== 'master') {
            $par = $par.closest('.epme-tabs');
        }
        if (delay === void 0) {
            delay = 100;
        }
        setTimeout(function () {
            window.epme_campaign.check_inputs($par);
        }, delay);
    };

    /**
     * Init block's events.
     */
    EPMEMessageBlocks.prototype.initial_tabs_events = function() {
        var _this = this;

        // Click to Add button (add new block)
        this.body.on('click', '.epme-tabs--fine-tune .epme-tab', {obj: _this}, function (event) {
            event.data.obj.fine_tune_tabs_click($(this));
        });
    };

    /**
     * Init message block interface.
     */
    EPMEMessageBlocks.prototype.fine_tune_tabs_click = function($this) {
        var this_id = $this.data('id'); // socialNetworkType

        this.render_blocks(this_id);
    };

    /**
     * Click handler to Delete block.
     */
    EPMEMessageBlocks.prototype.block_delete_handler = function($this) {
        var $par = $this.closest('.epme-message-block__menu'),
            this_type = $par.data('content-type'), // master or socialNetworkType
            this_order = $par.data('order');

        this.block_delete(this_type, this_order);
    };

    /**
     * Delete block.
     */
    EPMEMessageBlocks.prototype.block_delete = function(content_type, order) {
        var i = this.get_id(content_type, order),
            $par = $(this.dcm + '--' + content_type);

        switch (content_type) {
            case 'master':
                this._masterMessageBlock.splice(i, 1);
                for (var k = 0; k < this._masterMessageBlock.length; k++) {
                    this._masterMessageBlock[k].order = k + 1;
                }
                break;
            default:
                this._messageBlocks.splice(i, 1);
                var l = 1;
                for (var j = 0; j < this._messageBlocks.length; j++) {
                    if (this._messageBlocks[j].socialNetworkType === content_type) {
                        this._messageBlocks[j].order = l++;
                    }
                }
        }
        this.change_count($par, -1);
        this.render_blocks(content_type);
    };

    /**
     * Click handler to Send to preview.
     */
    EPMEMessageBlocks.prototype.send_to_preview_handler = function($this) {
        var this_content = $this.data('content');

        this.send_to_preview(this_content);
    };

    /**
     * Click handler to Refresh testers.
     */
    EPMEMessageBlocks.prototype.reload_campaign_footer_handler = function($this) {
        var this_type = $this.data('type'),
            $footer = $this.closest('.epme-message-build__footer');

        console.log($footer, this_type);
        this.server.reload_campaign_footer($footer, this_type);
    };

    /**
     * Send to preview.
     */
    EPMEMessageBlocks.prototype.send_to_preview = function(content_type) {
        var data;

        switch (content_type) {
            case 'master':
                data = this._masterMessageBlock;
                break;
            default:
                data = this.get_blocks_by_type(content_type);
        }

        this.server.send_to_preview(content_type, data);
    };

    /**
     * Set value for block by id.
     */
    EPMEMessageBlocks.prototype.set_block_value = function(content_type, order, val, extra_content) {
        var i = this.get_id(content_type, order);

        if (i === false) {
            return false;
        }

        switch (content_type) {
            case 'master':
                this._masterMessageBlock[i].content = val;
                this._masterMessageBlock[i].extra_content = extra_content;
                break;
            default:
                this._messageBlocks[i].content = val;
                this._messageBlocks[i].extra_content = extra_content;
        }
    };

    /**
     * Get position in array by order and content_type.
     */
    EPMEMessageBlocks.prototype.get_id = function(content_type, order) {
        switch (content_type) {
            case 'master':
                for (var i = 0; i < this._masterMessageBlock.length; i++) {
                    if (this._masterMessageBlock[i].order*1 === order*1) {
                        return i;
                    }
                }
                return this._masterMessageBlock.length - 1;
            default:
                var count = 0;

                for (var j = 0; j < this._messageBlocks.length; j++) {
                    if (this._messageBlocks[j].socialNetworkType === content_type) {
                        if (this._messageBlocks[j].order*1 === order*1) {
                            return j;
                        }
                        count++;
                    }
                }
                return count - 1;
        }
    };

    /**
     * Init message block interface.
     */
    EPMEMessageBlocks.prototype.add_button_click = function($this, is_added_block) {
        var content_type = $this.data('content-type');

        switch (content_type) {
            case 'master':
                if (this.is_changed_warning()) {
                    this._add_button = $this;
                    epme_open_dialog_modal(epme_texts('mb-content'), epme_texts('mb-title'), epme_texts('mb-understand'), epme_texts('mb-close'), 'epme-modal--block-change-button');
                } else {
                    this.add_block_by_button($this, is_added_block);
                }
                break;
            default:
                this.add_block_by_button($this, is_added_block);
        }
    };

    /**
     * Add block.
     */
    EPMEMessageBlocks.prototype.add_block_by_button = function($this, is_added_block) {
        var content_type = $this.data('content-type'),
            block_type = $this.data('id'),
            $par = $this.closest(this.dcm),
            $template = $this.next(this.dsb + '__template'),
            $block = $('#epme-source-message-block__content', $template),
            $message_body = $(this.dbuild + '__body', $par),
            $blocks_box = $(this.dmbs, $message_body);

        // Added content () to the message aria
        $blocks_box.append($block.text());

        var $new_block = $(this.dmb + '--new', $message_body),
            count = this.change_count($par, 1);

        this.change_block_id($new_block, count, content_type);
        $new_block.removeClass(this.mb + '--new');
        $new_block.data('content-type', content_type);

        if (epme_is_exist(is_added_block)) {
            this.add_block(content_type, {
                "socialNetworkType": content_type,
                "blockType": block_type,
                "content": '',
                "order": count,
            });
        }

        this.add_content($new_block, content_type, this.get_id(content_type, count));

        switch (block_type) {
            case 'text':
                var $textarea = $('div:not(.emojiPickerIconWrap) > .epme-textarea-emoji', $new_block);

                if ($textarea.length) {
                    $textarea.emojiPicker({
                        height: '300px',
                        width:  '450px'
                    });
                }
                break;
        }

        this.check_inputs($par, content_type);
    };

    /**
     * Add content (and extra-content) to the block.
     */
    EPMEMessageBlocks.prototype.add_content = function($block, content_type, i) {
        var $content_input = $('.epme-block-input--change', $block),
            content = '',
            extra_content = '',
            extra_type = '';

        switch (content_type) {
            case 'master':
                content = this._masterMessageBlock[i].content;
                extra_content = this._masterMessageBlock[i].extra_content;
                extra_type = this._masterMessageBlock[i].blockType;
                this._messageBlocks = [];
                break;
            default:
                content = this._messageBlocks[i].content;
                extra_content = this._messageBlocks[i].extra_content;
                extra_type = this._messageBlocks[i].blockType;
        }

        $content_input.val(content);

        switch (extra_type) {
            case 'image':
                var $images_prev = $('.epme-media__images', $block);

                if (content) {
                    epme_preview($images_prev, content);
                }
                break;
            default:
        }

        window.componentHandler.upgradeAllRegistered();
    };

    /**
     * Init message block interface.
     */
    EPMEMessageBlocks.prototype.change_block_id = function($block, new_id, content_type) {
        var old_id = $block.data('order'),
            $menu = $('.epme-message-block__menu', $block),
            $menu_button = $('.epme-message-block__menu-button', $block),
            new_attr_id = $menu.attr('for').replace(/%/g, new_id);


        $menu.data('order', new_id);
        $menu.data('content-type', content_type);  // master or socialNetworkType
        $menu.attr('for', new_attr_id);
        $menu_button.attr('id', new_attr_id);

        $block.data('order', new_id);
        $block.removeClass(this.mb + '--' + old_id);
        $block.addClass(this.mb + '--' + new_id);
    };

    /**
     * Change count of blocks.
     * val value -1 setup count to the zero.
     * val value 0 return current count.
     *
     * @param  $par
     * @param  val
     * @return int
     */
    EPMEMessageBlocks.prototype.change_count = function($par, val) {
        var $message_body = $(this.dbuild + '__body', $par),
            old_count = $message_body.data('count')*1,
            new_count = old_count + val*1;

        if (val === -1) {
            new_count = 0;
        }

        if (val !== 0) {
            $message_body.removeClass(this.build + '__body--' + old_count);
            $message_body.addClass(this.build + '__body--' + new_count);
            $message_body.data('count', new_count);
        }
        return new_count;
    };

    /**
     * Add block.
     *
     * @param  content_type * 'master' or channel
     * @param  data
     */
    EPMEMessageBlocks.prototype.add_block = function(content_type, data) {
        if (!epme_is_exist(data.blockType)) {
            return false;
        }

        switch (content_type) {
            case 'master':
                this._masterMessageBlock.push({
                    "blockType": data.blockType,
                    "content": (epme_is_exist(data.content)) ? data.content : '',
                    "order": (epme_is_exist(data.order)) ? data.order : this._masterMessageBlock.length + 1,
                    "_old_content": (epme_is_exist(data.content)) ? data.content : '',
                });
                break;
            default:
                this._messageBlocks.push({
                    "socialNetworkType": content_type,
                    "blockType": data.blockType,
                    "content": (epme_is_exist(data.content)) ? data.content : '',
                    "order": (epme_is_exist(data.order)) ? data.order : this.count_items(content_type) + 1,
                    "_old_content": (epme_is_exist(data.content)) ? data.content : '',
                });
        }
    };

    /**
     * Count blocks by type.
     *
     * @param  NetworkType
     * @return int
     */
    EPMEMessageBlocks.prototype.count_items = function(NetworkType) {
        return this.get_blocks_by_type(NetworkType).length;
    };

    /**
     * Render blocks.
     *
     * @param  content_type
     */
    EPMEMessageBlocks.prototype.render_blocks = function(content_type) {
        var $content = $(this.dcm + '--' + content_type),
            $buttons_box = $(this.dcm + '__left', $content),
            $message_box = $(this.dcm + '__right', $content),
            $epme_message_blocks = $(this.dmbs, $message_box), // box of Blocks
            blocks = this.get_blocks_by_type(content_type),
            $btn,
            _this = this;

        if (!$buttons_box.length || !$message_box.length) {
            return;
        }

        if (content_type !== 'master' && !blocks.length) {
            this.copy_master_to_channel(content_type);
            blocks = this.get_blocks_by_type(content_type);
        }

        this.change_count($content, -1);
        $epme_message_blocks.html('');

        blocks.sort(this.compare_order);
        for (var i = 0; i < blocks.length; i++) {
            $btn = $('.epme-source-message-block__add--' + blocks[i].blockType, $buttons_box);
            this.add_block_by_button($btn, false);
        }
        if (blocks.length) {
            this.check_inputs($content, content_type);
        }
    };

    /**
     * Sorting blocks.
     */
    EPMEMessageBlocks.prototype.compare_order = function(order1, order2) {
        return order1.order - order2.order;
    };

    /**
     * Return blocks by type.
     *
     * @param  content_type
     * @return array
     */
    EPMEMessageBlocks.prototype.get_blocks_by_type = function(content_type) {
        var blocks = [];

        switch (content_type) {
            case 'master':
                blocks = this._masterMessageBlock;
                break;
            default:
                for (var i = 0; i < this._messageBlocks.length; i++) {
                    if (epme_is_exist(this._messageBlocks[i].socialNetworkType) && this._messageBlocks[i].socialNetworkType === content_type) {
                        blocks.push(this._messageBlocks[i]);
                    }
                }
        }
        return blocks;
    };

    /**
     * Copy master blocks to channel.
     *
     * @param  content_type
     */
    EPMEMessageBlocks.prototype.copy_master_to_channel = function(content_type) {
        for (var i = 0; i < this._masterMessageBlock.length; i++) {
            this.add_block(content_type, this._masterMessageBlock[i]);
        }
    };

    /**
     * Set blocks variable.
     *
     * @param  master_blocks
     * @param  fine_tune_blocks
     */
    EPMEMessageBlocks.prototype.set_blocks_vars = function(master_blocks, fine_tune_blocks) {
        if (master_blocks.length) {
            this._masterMessageBlock = master_blocks;
        }
        if (fine_tune_blocks.length) {
            this._messageBlocks = fine_tune_blocks;
        }
    };

    /**
     * Return list of changed channels.
     *
     * @return array
     */
    EPMEMessageBlocks.prototype.get_changed_channels = function() {
        var chs = this.get_channels_in_memory(),
            chs_unique = [],
            ch;

        // Search for channels other than Master.
        for (var i = 0; i < chs.length; i++) {
            ch = this.get_blocks_by_type(chs[i]);
            if (!this.equal_channel_with_master(ch)) {
                chs_unique.push(chs[i]);
            }
        }
        return chs_unique;
    };

    /**
     * Test for equality channel with master.
     *
     * @return boolean
     */
    EPMEMessageBlocks.prototype.equal_channel_with_master = function(channel_blocks) {
        if (this._masterMessageBlock.length !== channel_blocks.length) {
            return false;
        }

        this._masterMessageBlock.sort(this.compare_order);
        channel_blocks.sort(this.compare_order);

        for (var i = 0; i < this._masterMessageBlock.length; i++) {
            if (this._masterMessageBlock[i].content !== channel_blocks[i].content ||
                this._masterMessageBlock[i].blockType !== channel_blocks[i].blockType) {
                return false;
            }
        }
        return true;
    };

    /**
     * Return list of channels in this._messageBlocks.
     *
     * @return array
     */
    EPMEMessageBlocks.prototype.get_channels_in_memory = function() {
        var ch = [];

        for (var i = 0; i < this._messageBlocks.length; i++) {
            if (this.find(ch, this._messageBlocks[i].socialNetworkType) === false) {
                ch.push(this._messageBlocks[i].socialNetworkType);
            }
        }
        return ch;
    };

    /**
     * Find value in array.
     */
    EPMEMessageBlocks.prototype.find = function(arr, val) {
        for (var j = 0; j < arr.length; j++) {
            if (arr[j] === val) {
                return j;
            }
        }
        return false;
    };

    /**
     * Return Master blocks.
     */
    EPMEMessageBlocks.prototype.get_master_blocks = function() {
        return this._masterMessageBlock;
    };

    /**
     * Return Fine-tune channel blocks.
     */
    EPMEMessageBlocks.prototype.get_message_blocks = function() {
        return this._messageBlocks;
    };

    window.EPMEMessageBlocks = EPMEMessageBlocks;

}($ || window.jQuery));
// end of file