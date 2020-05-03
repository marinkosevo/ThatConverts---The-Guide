jQuery(document).ready(function($) {

    jQuery(document.body).on('click', '.img_upload', function(e) {
        var img_upload = jQuery(this);

        e.preventDefault();
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: false,
            library: {
                type: 'image'
            },
        });

        image_frame.on('close', function() {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = gallery_ids.join(",");
            jQuery(img_upload).prev().val(ids);
            Refresh_Image(ids, img_upload);
        });

        image_frame.on('open', function() {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection = image_frame.state().get('selection');
            var id = jQuery(img_upload).prev().val();
            var attachment = wp.media.attachment(id);
            attachment.fetch();
            selection.add(attachment ? [attachment] : []);

        });

        image_frame.open();
    });

});

// Ajax request to refresh the image preview
function Refresh_Image(the_id, img_upload) {
    var data = {
        action: 'quiz_get_image',
        id: the_id
    };

    jQuery.get(ajaxurl, data, function(response) {
        if (response.success === true) {
            jQuery(img_upload).prev().prev().attr("src", response.data);
            jQuery(img_upload).prev().prev().css("display", 'block');
            console.log(response.data);
        }
    });
}