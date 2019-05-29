<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://webservicesrank.com
 * @since             1.0.0
 * @package           Open_Booking_Calendar_Plus
 *
 * @wordpress-plugin
 * Plugin Name:       Open Booking Calendar Plus
 * Plugin URI:        https://webservicesrank.com/wp-plugins/open-booking-calendar-plus/
 * Description:       Manage your hotel booking services (Free Plus features).
 * Version:           1.0.2
 * Author:            Web Services Rank
 * Author URI:        https://webservicesrank.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       open-booking-calendar-plus
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin Update Checker (GitHub Integration)
 */
require_once 'vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/webservicesrank/open-booking-calendar-plus',
	__FILE__,
	'open-booking-calendar-plus'
);

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'OPEN_BOOKING_CALENDAR_PLUS_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-open-booking-calendar-plus-activator.php
 */
function activate_open_booking_calendar_plus() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-open-booking-calendar-plus-activator.php';
	\OBCal\Open_Booking_Calendar_Plus_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-open-booking-calendar-plus-deactivator.php
 */
function deactivate_open_booking_calendar_plus() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-open-booking-calendar-plus-deactivator.php';
	\OBCal\Open_Booking_Calendar_Plus_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_open_booking_calendar_plus' );
register_deactivation_hook( __FILE__, 'deactivate_open_booking_calendar_plus' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-open-booking-calendar-plus.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_open_booking_calendar_plus() {

	$plugin = new \OBCal\Open_Booking_Calendar_Plus();
	$plugin->run();

}
run_open_booking_calendar_plus();
