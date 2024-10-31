(function($) {

    var $body = $('body'),
        $epme_cont = $('.epme-cont');

    $body.on('click', '.epme__upload_image_button', function() {
        var $this = $(this),
            $epme_single_com__field = $this.closest('.epme-media__upload-box');

        epme_uploader($epme_single_com__field, false);
        return false;
    });

    function epme_uploader($epme_single_com__field, multiple) {
        var $epme_single_com__images = $('.epme-media__images', $epme_single_com__field), // image preview
            $epme_single_com__media_upload = $('.epme-media__media-upload', $epme_single_com__field), // input with ID
            custom_uploader = null;

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image for ', //TODO title from PHP
            button: {
                text: 'Choose Image' //TODO from PHP
            },
            library : {
                type: 'image'
            },
            multiple: multiple
        });

        custom_uploader.on('select', function () {
            var selection = custom_uploader.state().get('selection'),
                ids = '';

            $epme_single_com__images.html('');

            var image = selection.models[0].attributes;
            var image2 = selection.models;

            epme_preview($epme_single_com__images, image.url);

            $epme_single_com__media_upload.val(image.url);
            $epme_single_com__media_upload.data('extra-content', image.id);
            $epme_single_com__media_upload.trigger('change');
        });
        custom_uploader.open();
    }

    function epme_preview($block, url) {
        $block.html('');
        $block.append('<div class="epme-media__single-image"><i class="epme-media__delete-img" onclick="epme_delete_img(this)"></i><div class="epme-media__upload-image" style="background-image: url('+url+'" data-img="'+url+'"></div></div>');
    }

    function epme_delete_img(el) {
        var $this = $(el),
            $img_container = $this.closest('.epme-media__single-image'),
            $img = $('.epme-media__upload-image', $img_container),
            image_id = $img.data('id'),
            $epme_single_com__field = $this.closest('.epme-media__field'),
            $epme_single_com__media_upload = $('.epme-media__media-upload', $epme_single_com__field), // input with ID
            ids = $epme_single_com__media_upload.val(),
            $epme_single_com__images = $('.epme-media__images', $epme_single_com__field);

        ids = ids.replace(image_id, '').replace(/\,{2,}/g, ',').replace(/[&#44;]{2,}/g, '&#44;').replace(/^&#44;|&#44;$/g, '').replace(/^,|,$/g, '');
        $epme_single_com__media_upload.val(ids);

        $img_container.remove();

        if (!$epme_single_com__images.children('.epme-media__single-image').length) {
            $epme_single_com__images.html('');
            $epme_single_com__images.append('<div class="epme-media__single-image"><div class="epme-media__upload-image" style="background-image: url('+$epme_single_com__images.data('src')+'"></div></div>');
        }

        return false;
    }

    /*function epme_get_img(id) {
        var data_sent = {
                'action': 'mammen_page_builder',
                'query': 'img_url_by_ID',
                'id': id,
                'size': 'large'
            };
        
        window.img = [];

        $.ajax({
            url: epme_ajaxurl.url,
            data: data_sent,
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            async: false,
            success: function(data){
                window.img = data;
            },
            error: function (xhr, ajaxOptions, thrownError) { // в случае неудачного завершения запроса к серверу
                console.error('gag-rating-@11: '+xhr.status); // покажем ответ сервера
                console.error('gag-rating-@12: '+thrownError); // и текст ошибки
            }
        });
        return window.img;
    }*/

    window.epme_uploader = epme_uploader;
    window.epme_delete_img = epme_delete_img;
    window.epme_preview = epme_preview;

})($ || window.jQuery);