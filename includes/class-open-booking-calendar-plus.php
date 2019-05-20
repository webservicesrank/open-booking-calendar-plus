<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webservicesrank.com
 * @since      1.0.0
 *
 * @package    Open_Booking_Calendar_Plus
 * @subpackage Open_Booking_Calendar_Plus/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Open_Booking_Calendar_Plus
 * @subpackage Open_Booking_Calendar_Plus/includes
 * @author     Web Services Rank <support@webservicesrank.com>
 */
class Open_Booking_Calendar_Plus {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Open_Booking_Calendar_Plus_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $open_booking_calendar_plus    The string used to uniquely identify this plugin.
	 */
	protected $open_booking_calendar_plus;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'OPEN_BOOKING_CALENDAR_PLUS_VERSION' ) ) {
			$this->version = OPEN_BOOKING_CALENDAR_PLUS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->open_booking_calendar_plus = 'open-booking-calendar-plus';

		$this->load_dependencies();
		$this->set_locale();
		$this->register_custom_post_types();
		$this->register_custom_meta_boxes();
		$this->register_custom_shortcodes();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Open_Booking_Calendar_Plus_Loader. Orchestrates the hooks of the plugin.
	 * - Open_Booking_Calendar_Plus_i18n. Defines internationalization functionality.
	 * - Open_Booking_Calendar_Plus_Admin. Defines all hooks for the admin area.
	 * - Open_Booking_Calendar_Plus_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-open-booking-calendar-plus-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-open-booking-calendar-plus-i18n.php';

		/**
		 * The classes responsible for register Custom Post Types.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-types/class-promotion-cpt.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-types/class-contact-log-cpt.php';

		/**
		 * The classes responsible for register Custom Meta Boxes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/meta-boxes/class-promotion-cmb.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/meta-boxes/class-contact-log-cmb.php';

		/**
		 * The classes responsible for register Custom Shortcodes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/class-promotions-csc.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-open-booking-calendar-plus-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-open-booking-calendar-plus-public.php';

		$this->loader = new Open_Booking_Calendar_Plus_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Open_Booking_Calendar_Plus_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Open_Booking_Calendar_Plus_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Add Custom Post Types.
	 * 
	 * @since	1.0.0
	 * @access	private
	 */
	private function register_custom_post_types() {

		/**
		 * Add 'Promotion custom post type' actions an filters.
		 */
		$promotion_cpt = new Promotion_CPT( $this->get_open_booking_calendar_plus(), $this->get_version() );
		$this->loader->add_action( 'init', $promotion_cpt, 'register', 11);
		$this->loader->add_filter( 'obcal_promotion_get_promotion_id', $promotion_cpt, 'get_promotion_id', 10, 3);

		/**
		 * Add 'Contact custom post type' actions an filters.
		 */
		$contact_log_cpt = new ContactLog_CPT( $this->get_open_booking_calendar_plus(), $this->get_version() );
		$this->loader->add_action( 'init', $contact_log_cpt, 'register', 11);
		$this->loader->add_filter( 'obcal_contact_log_insert_contact', $contact_log_cpt, 'insert_contact', 10, 2);
		$this->loader->add_action( 'obcal_contact_log_insert_query_log', $contact_log_cpt, 'insert_query_log', 10, 3);

	}

	/**
	 * Add Custom Meta Boxes.
	 * 
	 * @since	1.0.0
	 * @access	private
	 */
	private function register_custom_meta_boxes() {

		/**
		 * Add 'Promotion custom meta boxes' actions an filters.
		 */
		$promotion_cmb = new Promotion_CMB( $this->get_open_booking_calendar_plus(), $this->get_version() );
		$this->loader->add_action( 'add_meta_boxes', $promotion_cmb, 'register');
		$this->loader->add_action( 'save_post_obcal_promotion', $promotion_cmb, 'save');
		$this->loader->add_filter( 'manage_obcal_promotion_posts_columns', $promotion_cmb, 'add_table_custom_columns');
		$this->loader->add_action( 'manage_obcal_promotion_posts_custom_column', $promotion_cmb, 'add_table_custom_values', 10, 2);
		$this->loader->add_filter( 'manage_edit-obcal_promotion_sortable_columns', $promotion_cmb, 'register_table_sortable_columns');

		/**
		 * Add 'Contact Log custom meta boxes' actions an filters.
		 */
		$contact_log_cmb = new ContactLog_CMB( $this->get_open_booking_calendar_plus(), $this->get_version() );
		$this->loader->add_action( 'add_meta_boxes', $contact_log_cmb, 'register');
		$this->loader->add_action( 'save_post_obcal_contact_log', $contact_log_cmb, 'save');
		$this->loader->add_filter( 'manage_obcal_contact_log_posts_columns', $contact_log_cmb, 'add_table_custom_columns');
		$this->loader->add_action( 'manage_obcal_contact_log_posts_custom_column', $contact_log_cmb, 'add_table_custom_values', 10, 2);
		$this->loader->add_filter( 'manage_edit-obcal_contact_log_sortable_columns', $contact_log_cmb, 'register_table_sortable_columns');

	}

	/**
	 * Add Custom Shortcodes.
	 * 
	 * @since	1.0.0
	 * @access	private
	 */
	private function register_custom_shortcodes() {

		/**
		 * Add 'Promotion list custom shortcode' actions an filters.
		 */
		$promotions_csc = new Promotions_CSC( $this->get_open_booking_calendar_plus(), $this->get_version() );
		$this->loader->add_action( 'init', $promotions_csc, 'register');

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Open_Booking_Calendar_Plus_Admin( $this->get_open_booking_calendar_plus(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 11 ); // 11 for load after non Plus plugin
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 11 ); // 11 for load after non Plus plugin

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Open_Booking_Calendar_Plus_Public( $this->get_open_booking_calendar_plus(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 11 ); // 11 for load after non Plus plugin
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 11 ); // 11 for load after non Plus plugin

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_open_booking_calendar_plus() {
		return $this->open_booking_calendar_plus;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Open_Booking_Calendar_Plus_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
