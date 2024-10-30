<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Bpcustomerio
 * @subpackage Bpcustomerio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bpcustomerio
 * @subpackage Bpcustomerio/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Bpcustomerio_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bpcustomerio-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bpcustomerio-public.js', array( 'jquery' ), $this->version, false );

	}

	public function chk_users_to_bpgroup() {
		global $bp,$wpdb;
		$is_enable = get_option('bpcustomerio_enable',true);
		$site_id = get_option('bpcustomerio_site_id',true);
		$api_key = get_option('bpcustomerio_appid',true);
		$current_user = wp_get_current_user();
               
		$SQL = "SELECT * FROM {$wpdb->prefix}bp_xprofile_data WHERE user_id = '{$current_user->ID}' ";
                $resultset = $this->get_result_by_query_Associtive($SQL);
                
                if (!empty($resultset)) {
                        $user_fileds = count($resultset);

                        $get_count = $wpdb->get_row("SELECT count(*) as cnt FROM {$wpdb->prefix}bp_xprofile_fields");
                        $total_fields = $get_count->cnt;

                        if (isset($is_enable) && $is_enable == 'yes' && isset($site_id) && !empty($site_id) && isset($api_key) && !empty($api_key) && is_user_logged_in() && $total_fields != $user_fileds) {

                            global $current_user;
                            $user_id = get_current_user_id();
                            $session = curl_init();
                            $customer_id = 'bp_' . $user_id; // You'll want to set this dynamically to the unique id of the user associated with the event
                            $customerio_url = 'https://track.customer.io/api/v1/customers/' . $customer_id . '/events';


                            $data = array('name' => 'profile_not_completed');

                            curl_setopt($session, CURLOPT_URL, $customerio_url);
                            curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                            curl_setopt($session, CURLOPT_HEADER, false);
                            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($session, CURLOPT_VERBOSE, 1);
                            curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'POST');
                            curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));
                            curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

                            curl_setopt($session, CURLOPT_USERPWD, $site_id . ':' . $api_key);

                            curl_exec($session);
                            curl_close($session);
                        }
                }
    }
	
	public function get_result_by_query_Associtive($SQL = null) {
                global $bp, $wpdb;
                $results = $wpdb->get_results("$SQL", ARRAY_A);
                return $results;
            }
	
	public function register_script_footer() { 
		$get_siteid = get_option('bpcustomerio_site_id',true);
		$site_id = isset($get_siteid) ? $get_siteid :'';
		$current_user = wp_get_current_user();
		$user_id = get_current_user_id();
		?>
	
	<script type="text/javascript">
	
		 var _cio = _cio || [];
    (function() {
      var a,b,c;a=function(f){return function(){_cio.push([f].
      concat(Array.prototype.slice.call(arguments,0)))}};b=["load","identify",
      "sidentify","track","page"];for(c=0;c<b.length;c++){_cio[b[c]]=a(b[c])};
      var t = document.createElement('script'),
          s = document.getElementsByTagName('script')[0];
      t.async = true;
      t.id    = 'cio-tracker';
      t.setAttribute('data-site-id', '<?php echo $site_id;?>');
      t.src = 'https://assets.customer.io/assets/track.js';
      s.parentNode.insertBefore(t, s);
    })();
 
    <?php 
    
   	    $current_user = wp_get_current_user();
		global $wpdb;
		$SQL = "SELECT * FROM {$wpdb->prefix}bp_xprofile_data WHERE user_id = '{$current_user->ID}' ";
       	$resultset = $this->get_result_by_query_Associtive($SQL);
		
       	$count_user_filed = count($resultset);
       	
		$get_count = $wpdb->get_row("SELECT count(*) as cnt FROM {$wpdb->prefix}bp_xprofile_fields");
       	$total_fields = $get_count->cnt;
       	$profile_percentage = ($count_user_filed/$total_fields)*100;
       	
       	$customer_id = 'bp_'.$user_id;
       	?>
      _cio.identify({
      // Required attributes
      id:'<?php echo $customer_id;?>',           // Unique id for the currently signed in user in your application.
      email: '<?php echo $current_user->user_email;?>', // Email of the currently signed in user.
      created_at: <?php echo date("U", strtotime( $current_user->user_registered )); ?>,   // Timestamp in your system that represents when
                               

      // Optional (these are examples. You can name attributes what you wish)

      first_name: '<?php echo $current_user->user_login;?>',       // Add any attributes you'd like to use in the email subject or body.
      profile_percentage: '<?php echo $profile_percentage;?>', 
         
    });
	
	</script>
		
	<?php }
	
	/**
	 * BN code added 
	 */
	function paypal_bn_code_filter_bpcustomerio($paypal_args) {
		$paypal_args['bn'] = 'Multidots_SP';
		return $paypal_args;
	}
}
