var ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";

setInterval(function() {
    //jQuery('#number').html(res.counter.toLocaleString());
    var counter = jQuery('#number').text();
    //var counter = "<?php //echo ec_overload(); ?>";
    ++counter;
    var ajaxnonce = "<?php echo wp_create_nonce(-1);?>";
    jQuery(document).ready(function() {
        var data = {
            'action': 'ec_update_overload',
            'counter': counter,
            'nonce': ajaxnonce
        };

        jQuery.post(ajaxurl, data, function(response) {
            var res = jQuery.parseJSON(response);
            console.log(res);
            jQuery('#number').html(res.counter.toLocaleString());
        });
    });

}, 3000);