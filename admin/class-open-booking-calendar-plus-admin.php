<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webservicesrank.com
 * @since      1.0.0
 *
 * @package    Open_Booking_Calendar_Plus
 * @subpackage Open_Booking_Calendar_Plus/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Open_Booking_Calendar_Plus
 * @subpackage Open_Booking_Calendar_Plus/admin
 * @author     Web Services Rank <support@webservicesrank.com>
 */
class Open_Booking_Calendar_Plus_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $open_booking_calendar_plus    The ID of this plugin.
	 */
	private $open_booking_calendar_plus;

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
	 * @param      string    $open_booking_calendar_plus       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $open_booking_calendar_plus, $version ) {

		$this->open_booking_calendar_plus = $open_booking_calendar_plus;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Open_Booking_Calendar_Plus_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Open_Booking_Calendar_Plus_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->open_booking_calendar_plus, plugin_dir_url( __FILE__ ) . 'css/open-booking-calendar-plus-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Open_Booking_Calendar_Plus_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Open_Booking_Calendar_Plus_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->open_booking_calendar_plus, plugin_dir_url( __FILE__ ) . 'js/open-booking-calendar-plus-admin.js', array( 'jquery' ), $this->version, false );

	}

}
