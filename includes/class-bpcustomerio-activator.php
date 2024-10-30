<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Bpcustomerio
 * @subpackage Bpcustomerio/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bpcustomerio
 * @subpackage Bpcustomerio/includes
 * @author     Multidots <inquiry@multidots.in>
 */
class Bpcustomerio_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
                if( !in_array( 'buddypress/bp-loader.php',apply_filters('active_plugins',get_option('active_plugins'))) && !is_plugin_active_for_network( 'buddypress/bp-loader.php' )   ) {
			wp_die( "<strong>BuddyPress Customer.io Analytics Integration </strong> Plugin requires <strong>BuddyPress</strong> <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
		}
		set_transient( '_welcome_screen_buddypresscustomer_io_activation_redirect_data', true, 30 );	
	}

}
