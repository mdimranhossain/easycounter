<?php
/**
 * Plugin Name:       EasyCounter
 * Plugin URI:        https://bhubs.com/plugins/easycounter/
 * Description:       Easy Counter.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Imran Hossain
 * Author URI:        https://mdimranhossain.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://bhubs.com/plugins/easycounter/
 * Text Domain:       easycounter
 */

declare(strict_types=1);

add_action( 'wp_ajax_ec_update_overload', 'ec_update_overload');

add_action( 'wp_ajax_nopriv_ec_update_overload', 'ec_update_overload');

function ec_update_overload(){
    $result = [];
    if(!empty($_REQUEST['counter'])){
        
        $counter = intval($_REQUEST['counter']);
        // $result['req'] = intval($_REQUEST['counter']);
        $saved = intval(get_option('generated_overload'));
        $result['saved'] = $saved;
        if($counter<=$saved){
            $counter = $saved + 1;
        }

        $result['counter'] = $counter;

        if(update_option('generated_overload',$counter))
        {
            echo json_encode($result);
        }else{
            echo json_encode(['error'=>'Something went wrong!!!']);
        }
    }
    
    wp_die();
}


function ec_script(){
    ?>
<script>
var ajaxurl = "<?php echo admin_url('admin-ajax.php');?>";

setInterval(function() {
    var counter = parseInt(jQuery('#number').text());
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

}, 5000);
</script>
<?php
}


function ec_overload(){ 
    $counter = get_option('generated_overload');
    $output = '<div id="number">'.$counter.'</div>';
    ec_script();
    return $output;
} 

function ec_shortcode(){
    add_shortcode('counter', 'ec_overload'); 
}
add_action( 'init','ec_shortcode');
 
 
/**
 * Activate the plugin.
 */
function ec_activate()
{
    global $ec_db_version;
    $ec_db_version = '1.0.0';

    $counter = get_option('generated_overload');
    
    if(empty($counter)){
        $counter=1237500;
        add_option('generated_overload',$counter);
    }
}

function ec_deactivate()
{
    //Nothing to do here this case
}

function ec_uninstall()
{
    // Nothing to trigger here for this plugins
}

register_activation_hook( __FILE__, 'ec_activate' );

register_deactivation_hook( __FILE__, 'ec_deactivate' );

register_uninstall_hook(__FILE__, 'ec_uninstall');