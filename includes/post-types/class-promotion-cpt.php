<?php

class Promotion_CPT 
{

	/**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $open_booking_calendar    The ID of this plugin.
     */
    private $open_booking_calendar;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

	protected $post_type = 'obcal_promotion';

	public static $mainAdminMenuCapability = 'edit_posts';
	public static $mainAdminMenuSlug = 'open-booking-calendar';
	public static $mainAdminMenuPosition = 3.5;

	/**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $open_booking_calendar       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($open_booking_calendar, $version)
    {

        $this->open_booking_calendar = $open_booking_calendar;
        $this->version = $version;
	}
	
	public function register(){

		$labels = array(
			'name'					 => __( 'Promotions', 'open-booking-calendar-plus' ),
			'singular_name'			 => __( 'Promotion', 'open-booking-calendar-plus' ),
			'add_new'				 => _x( 'Add New', 'Add New Season', 'open-booking-calendar-plus' ),
			'add_new_item'			 => __( 'Add New Promotion', 'open-booking-calendar-plus' ),
			'edit_item'				 => __( 'Edit Promotion', 'open-booking-calendar-plus' ),
			'new_item'				 => __( 'New Promotion', 'open-booking-calendar-plus' ),
			'view_item'				 => __( 'View Promotion', 'open-booking-calendar-plus' ),
			'search_items'			 => __( 'Search Promotion', 'open-booking-calendar-plus' ),
			'not_found'				 => __( 'No promotions found', 'open-booking-calendar-plus' ),
			'not_found_in_trash'	 => __( 'No promotions found in Trash', 'open-booking-calendar-plus' ),
			'all_items'				 => __( 'Promotions', 'open-booking-calendar-plus' ),
			'insert_into_item'		 => __( 'Insert into promotion description', 'open-booking-calendar-plus' ),
			'uploaded_to_this_item'	 => __( 'Uploaded to this promotion', 'open-booking-calendar-plus' )
		);

		$args = array(
			'labels'				 => $labels,
			'description'			 => __( 'This is where you can add new promotions.', 'open-booking-calendar-plus' ),
			'public'				 => false,
			'publicly_queryable'	 => false,
			'show_ui'				 => true,
			'query_var'				 => false,
			'capability_type'		 => 'post',
			'has_archive'			 => false,
			'hierarchical'			 => false,
			'show_in_menu'			 => self::$mainAdminMenuSlug,
			'supports'				 => array( 'title' ),
			'hierarchical'			 => false,
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Determine and return the corresponding promotion id
	 */
	public function get_promotion_id($accommodation_id, $season_id, $num_nights)
	{

		$promotion_id = ""; // empty by default

		// 
		$args = [
			'meta_query' => [
				'relation' => 'AND',
				[
					'key' => '_obcal_promotion_accommodation_id',
					'value' => $accommodation_id
				],
				[
					'key' => '_obcal_promotion_season_id',
					'value' => $season_id
				],
				[
					'key' => '_obcal_promotion_num_nights',
					'value' => $num_nights,
					'type' => 'NUMERIC', // specify it for numeric values
					'compare' => '<='
				],
			],
			'post_type' => 'obcal_promotion',
		];

		$query_result = new WP_Query($args);

		// The Loop
		if ($query_result->have_posts()) {

			$query_result->the_post();
			$post = $query_result->post;

			// Set promotion_id
			$promotion_id = $post->ID;

			/* Restore original Post Data */
			wp_reset_postdata();
		}

		return $promotion_id;
	}

}