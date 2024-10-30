<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bpcustomerio
 * @subpackage Bpcustomerio/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Bpcustomerio_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();
		

	}
	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if (isset($_GET['page']) && !empty($_GET['page']) && ($_GET['page'] == 'bpcustomerio')) {

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bpcustomerio-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'wp-pointer' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() { 
	    if (isset($_GET['page']) && !empty($_GET['page']) && ($_GET['page'] == 'bpcustomerio')) {

			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bpcustomerio-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'wp-pointer' );
		}
	}
	
	public function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bpcustomerio-admin-display.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-bpcustomerio-html-output.php';

	}

    public function custom_buddypresscustomer_io_pointers_footer(){ 
		$admin_pointers = custom_buddypresscustomer_io_pointers_admin_pointers();
	    ?>
		    <script type="text/javascript">
		        /* <![CDATA[ */
		        ( function($) {
		            <?php
		            foreach ( $admin_pointers as $pointer => $array ) {
		               if ( $array['active'] ) {
		                  ?>
		            $( '<?php echo $array['anchor_id']; ?>' ).pointer( {
		                content: '<?php echo $array['content']; ?>',
		                position: {
		                    edge: '<?php echo $array['edge']; ?>',
		                    align: '<?php echo $array['align']; ?>'
		                },
		                close: function() {
		                    $.post( ajaxurl, {
		                        pointer: '<?php echo $pointer; ?>',
		                        action: 'dismiss-wp-pointer'
		                    } );
		                }
		            } ).pointer( 'open' );
		            <?php
		         }
		      }
		      ?>
		        } )(jQuery);
		        /* ]]> */
		    </script>
		<?php
	}
	
	// Function For Welcome page to plugin 
    public function welcome_buddypresscustomer_io_screen_do_activation_redirect (){ 
    	
    	if (!get_transient('_welcome_screen_buddypresscustomer_io_activation_redirect_data')) {
			return;
		}
		
		// Delete the redirect transient
		delete_transient('_welcome_screen_buddypresscustomer_io_activation_redirect_data');

		// if activating from network, or bulk
		if (is_network_admin() || isset($_GET['activate-multi'])) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect(add_query_arg(array('page' => 'buddypress_customer_io&tab=about'), admin_url('index.php')));
    }
    
	public function welcome_pages_screen_buddypresscustomer_io() {
		add_dashboard_page(
		'BuddyPress Customer.io Analytics Integration Dashboard', 'BuddyPress Customer.io Analytics Integration Dashboard', 'read', 'buddypress_customer_io', array(&$this, 'welcome_screen_content_buddypress_customer_io')
		);
	}
    
	public function welcome_screen_content_buddypress_customer_io(){ 
        ?>
        
         <div class="wrap about-wrap">
            <h1 style="font-size: 2.1em;"><?php printf(esc_html__('Welcome to BuddyPress Customer.io Analytics Integration', 'bpcustomerio')); ?></h1>

            <div class="about-text woocommerce-about-text">
        <?php
        $message = '';
        printf(esc_html__('%s BuddyPress Customer.io Analytics Integration helps you to set event for BuddyPress users.', 'bpcustomerio'), $message, $this->version);
        ?>
                <img class="version_logo_img" src="<?php echo esc_url( plugin_dir_url(__FILE__) . 'images/buddypresscustomer_io.png') ; ?>">
            </div>

        <?php
        $setting_tabs_wc = apply_filters('woocustomer_io_setting_tab', array("about" => "Overview", "other_plugins" => "Checkout our other plugins"));
        $current_tab_wc = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
        $aboutpage = isset($_GET['page'])
        ?>
            <h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs_wc as $name => $label)
            echo '<a  href="' . esc_url( admin_url('index.php?page=buddypress_customer_io&tab=' . urlencode($name)  ) ). '" class="nav-tab ' . ( $current_tab_wc == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
            </h2>

                <?php
                foreach ($setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue) {
                	switch ($setting_tabkey_wc) {
                		case $current_tab_wc:
                			do_action('buddypresscustomer_io_' . $current_tab_wc);
                			break;
                	}
                }
                ?>
            <hr />
            <div class="return-to-dashboard">
                <a href="<?php echo esc_url( admin_url('admin.php?page=bpcustomerio') ); ?>"><?php esc_html_e('Go to BuddyPress Customer.io Analytics Integration Settings', 'bpcustomerio'); ?></a>
            </div>
        </div>
	<?php }
	
	/**
     * About tab content of Add social share button about
     *
     */
	public function buddypresscustomer_io_about() {
		//do_action('my_own');
		$current_user = wp_get_current_user();
    	?>
        <div class="changelog">
            </br>
           	<style type="text/css">
				p.buddypresscustomer_io_overview {max-width: 100% !important;margin-left: auto;margin-right: auto;font-size: 15px;line-height: 1.5;}
				.buddypresscustomer_io_ul ul li {margin-left: 3%;list-style: initial;line-height: 23px;}
			</style>  
            <div class="changelog about-integrations">
                <div class="wc-feature feature-section col three-col">
                    <div>
                    
                    <p class="buddypresscustomer_io_overview"><?php _e('Buddypress customer.io analytics integration helps you to set an event for BuddyPress users. It integrates with customer.io through API and allows the communication between buddypress site and customer.io system.', 'bpcustomerio'); ?></p>
                        
                         <p class="buddypresscustomer_io_overview"><strong>Plugin Functionality: </strong></p> 
                        <div class="buddypresscustomer_io_ul">
                        	<ul>
								<li>Easy setup no specialization required to use User friendly interface.</li>
								<li>Add Site ID and API Key of customer io.</li>
								<li>Set up event for send and emails to users for complete their profile.</li>
							</ul>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
	<?php  }
	
	public function adjust_the_wp_menu_buddypresscustomer_io(){ 
		remove_submenu_page('index.php', 'buddypress_customer_io');
	}
    
}

function custom_buddypresscustomer_io_pointers_admin_pointers(){ 
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$version = '1_0'; // replace all periods in 1.0 with an underscore
	$prefix = 'custom_buddypresscustomer_io_pointers' . $version . '_';

	$new_pointer_content = '<h3>' . esc_html__( 'Welcome to BuddyPress Customer.io Analytics Integration','bpcustomerio'  ) . '</h3>';
	$new_pointer_content .= '<p>' . esc_html__( 'Buddypress customer.io analytics integration helps you to set an event for BuddyPress users. It integrates with customer.io through API and allows the communication between buddypress site and customer.io system.', 'bpcustomerio' ) . '</p>';

	return array(
		$prefix . 'buddypresscustomer_io_notice_view' => array(
			'content' => $new_pointer_content,
			'anchor_id' => '#adminmenu',
			'edge' => 'left',
			'align' => 'left',
			'active' => ( ! in_array( $prefix . 'buddypresscustomer_io_notice_view', $dismissed ) )
		)
	);
}