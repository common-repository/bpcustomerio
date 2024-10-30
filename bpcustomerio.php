<?php

/**
 * Plugin Name:       BuddyPress Customer.io Analytics Integration
 * Plugin URI:        http://www.multidots.com
 * Description:       Buddypress customer.io analytics integration helps you to set an event for BuddyPress users. It integrates with customer.io through API and allows the communication between buddypress site and customer.io system.
 * Version:           1.1.6
 * Author:            Multidots
 * Author URI:        http://www.multidots.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bpcustomerio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bpcustomerio-activator.php
 */
function activate_bpcustomerio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bpcustomerio-activator.php';
	Bpcustomerio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bpcustomerio-deactivator.php
 */
function deactivate_bpcustomerio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bpcustomerio-deactivator.php';
	Bpcustomerio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bpcustomerio' );
register_deactivation_hook( __FILE__, 'deactivate_bpcustomerio' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bpcustomerio.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bpcustomerio() {

	$plugin = new Bpcustomerio();
	$plugin->run();

}
run_bpcustomerio();
