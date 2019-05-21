<?php

namespace OBCal;

class ContactLog_CMB
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

	protected $post_type = 'obcal_contact_log';

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
	
	/**
	 * Register Meta Boxes.
	 */
	public function register(){

		add_meta_box(
			"{$this->post_type}_details",										// Unique ID
			__('Contact details', 'open-booking-calendar-plus'),						// Box title
			[$this, 'details_html'],  											// Content callback, must be of type callable
			$this->post_type             										// Post type
		);

		add_meta_box(
			"{$this->post_type}_queries_details",								// Unique ID
			__('Queries details', 'open-booking-calendar-plus'),						// Box title
			[$this, 'queries_details_html'],									// Content callback, must be of type callable
			$this->post_type             										// Post type
		);

	}

	/**
	 * HTML content of the Details Meta Box.
	 */
	public function details_html($post)
	{
		$email = get_post_meta($post->ID, "_{$this->post_type}_email", true);
		$last_query_date = new \DateTime(get_post_meta($post->ID, "_{$this->post_type}_last_query_date", true));
		$num_queries = count(get_post_meta( $post->ID , "_{$this->post_type}_query" , false ));

		// Get the options
		$options = get_option('obcal_options');

		// Get date format
		$options_date_format = isset($options['obcal_field_date_format']) ? $options['obcal_field_date_format'] : 'Y-m-d';

		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="<?= esc_attr($this->post_type . '_email') ?>"><?php esc_html_e('Email', 'open-booking-calendar-plus'); ?></label>
				</th>
				<td>
					<input type="text" name="<?= esc_attr($this->post_type . '_email') ?>" id="<?= esc_attr($this->post_type . '_email') ?>" class="contact-log-email" placeholder="<?php esc_html_e('Email', 'open-booking-calendar-plus'); ?>" value="<?=$email?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php esc_html_e('Last query date', 'open-booking-calendar-plus'); ?></label>
				</th>
				<td>
					<?= esc_html($last_query_date->format($options_date_format . ' H:i:s'))?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php esc_html_e('Number of queries', 'open-booking-calendar-plus'); ?></label>
				</th>
				<td>
					<?= esc_html($num_queries) ?>
				</td>
			</tr>
		</table>			
		<?php
	}

	/**
	 * HTML content of the Queries Details Meta Box.
	 */
	public function queries_details_html($post)
	{

		// Get the options
		$options = get_option('obcal_options');

		// Get date format
		$options_date_format = isset($options['obcal_field_date_format']) ? $options['obcal_field_date_format'] : 'Y-m-d';

		$query_types = [
			'booking_preview' => __('Booking preview', 'open-booking-calendar-plus'),
			'search_results' => __('Accommodation search', 'open-booking-calendar-plus'),
		];

		$queries = get_post_meta($post->ID, "_{$this->post_type}_query", false);

		?>
		<table class="form-table">
			<?php
				$num = 1;
				foreach ($queries as $query) {
			?>
			<tr>
				<th scope="row">
					#<?=$num?><br>
					<?= esc_html( __('Type', 'open-booking-calendar-plus') . ': ' . $query_types[$query['query_type']] ) ?>
				</th>
				<td>
					<?php
					
					if ($query['query_type'] == 'booking_preview') {

						echo $this->show_booking_preview_query_details($query, $options_date_format);

					} else if ($query['query_type'] == 'search_results') {

						echo $this->show_search_results_query_details($query, $options_date_format);

					}
					
					?>
				</td>
			</tr>
			<?php
					$num++;
				}
			?>
		</table>			
		<?php
	}

	/**
	 * Show the query details of 'booking_preview' query type
	 */
	public function show_booking_preview_query_details($query, $options_date_format) {

		$o = '<table class="inner-form-table">';

		if (array_key_exists("query_date", $query)) {

			$query_date = new \DateTime($query['query_date']);
			$o .= '<tr><th scope="row">';
			$o .= esc_html__('Query date', 'open-booking-calendar-plus');
			$o .= '</th><td>';
			$o .= esc_html($query_date->format($options_date_format . ' H:i:s'));
			$o .= '</td></tr>';

		}

		if (array_key_exists("post_array", $query)) {

			if (array_key_exists("accommodation_id", $query['post_array'])) {

				$accommodation = get_post($query['post_array']['accommodation_id']);
				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Accommodation', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($accommodation->post_title);
				$o .= '</td></tr>';

			}

			if (array_key_exists("selected_date", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Selected date', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['selected_date']);
				$o .= '';

			}

			if (array_key_exists("num_adults", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Number of adults', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['num_adults']);
				$o .= '</td></tr>';

			}

			if (array_key_exists("num_children", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Number of children', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['num_children']);
				$o .= '</td></tr>';

			}

			if (array_key_exists("us_name", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Name', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['us_name']);
				$o .= '</td></tr>';

			}

		}

		$o .= '</table>';

		return $o;

	}

	/**
	 * Show the query details of 'search_results' query type
	 */
	public function show_search_results_query_details($query, $options_date_format) {

		$o = '<table class="inner-form-table">';

		if (array_key_exists("query_date", $query)) {

			$query_date = new \DateTime($query['query_date']);
			$o .= '<tr><th scope="row">';
			$o .= esc_html__('Query date', 'open-booking-calendar-plus');
			$o .= '</th><td>';
			$o .= esc_html($query_date->format($options_date_format . ' H:i:s'));
			$o .= '</td></tr>';

		}

		if (array_key_exists("post_array", $query)) {

			if (array_key_exists("selected_date", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Selected date', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['selected_date']);
				$o .= '';

			}

			if (array_key_exists("num_adults", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Number of adults', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['num_adults']);
				$o .= '</td></tr>';

			}

			if (array_key_exists("num_children", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Number of children', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['num_children']);
				$o .= '</td></tr>';

			}

			if (array_key_exists("us_name", $query['post_array'])) {

				$o .= '<tr><th scope="row">';
				$o .= esc_html__('Name', 'open-booking-calendar-plus');
				$o .= '</th><td>';
				$o .= esc_html($query['post_array']['us_name']);
				$o .= '</td></tr>';

			}

		}

		$o .= '</table>';

		return $o;

	}

	/**
	 * Save data of Meta Boxes.
	 */
	public function save($post_id)
	{

		// Keys of the values to save directly
		$keys_to_save_directly = ['email'];

		/** 
		 * Sanitize POST values
		 */

		// Sanitize values for 'Save values in array directly'
		foreach ($keys_to_save_directly as $key_to_save) {
			if (array_key_exists("{$this->post_type}_{$key_to_save}", $_POST)) {
				if ($key_to_save == 'email') {
					$_POST["{$this->post_type}_{$key_to_save}"] = sanitize_email($_POST["{$this->post_type}_{$key_to_save}"]);
				}
			}
		}


		/**
		 * Validate POST values
		 */

		// Validate values for 'Save values in array directly'
		foreach ($keys_to_save_directly as $key_to_save) {
			if (array_key_exists("{$this->post_type}_{$key_to_save}", $_POST)) {

				// Get POST value
				$value = $_POST["{$this->post_type}_{$key_to_save}"];

				// Validate 'email'

			}
		}
		
		/** 
		 * Save values in array directly
		 */

		foreach ($keys_to_save_directly as $key_to_save) {
			if (array_key_exists("{$this->post_type}_{$key_to_save}", $_POST)) {
				update_post_meta(
					$post_id,
					"_{$this->post_type}_{$key_to_save}",
					$_POST["{$this->post_type}_{$key_to_save}"]
				);
			}
		}

	}

	/**
	 * Add Custom Columns to the Post Type Table.
	 */
	public function add_table_custom_columns($columns) {
		$new_columns = [
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'email' => esc_html__('Email', 'open-booking-calendar-plus'),
			'last_query_date' => esc_html__('Last query date', 'open-booking-calendar-plus'),
			'num_queries' => esc_html__('Queries', 'open-booking-calendar-plus'),
			'date' => $columns['date'],
		];
		return $new_columns;
	}

	/**
	 * Add Custom Columns Data to the Post Type Table.
	 */
	public function add_table_custom_values( $column, $post_id ) {
		switch ( $column ) {
			case 'email':
				echo esc_html(get_post_meta( $post_id , "_{$this->post_type}_email" , true ));
				break;
			case 'last_query_date':
				// Get the options
				$options = get_option('obcal_options');
				// Get date format
				$options_date_format = isset($options['obcal_field_date_format']) ? $options['obcal_field_date_format'] : 'Y-m-d';
				//
				$last_query_date = new \DateTime(get_post_meta( $post_id , "_{$this->post_type}_last_query_date" , true ));
				echo esc_html($last_query_date->format($options_date_format . ' H:i:s'));
				break;
			case 'num_queries':
				$num_queries = count(get_post_meta( $post_id , "_{$this->post_type}_query" , false ));
				echo esc_html($num_queries);
				break;
		}
	}

	/**
	 * Register the columns as sortable.
	 */
	public function register_table_sortable_columns( $columns ) {
		$columns['email'] = 'Email';
		$columns['last_query_date'] = 'Last query date';
		$columns['num_queries'] = 'Queries';
		return $columns;
	}
}