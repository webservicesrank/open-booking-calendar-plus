<?php

class Promotion_CMB
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
			"{$this->post_type}_details",													// Unique ID
			__('Promotion details', 'open-booking-calendar-plus'),			// Box title
			[$this, 'details_html'],  														// Content callback, must be of type callable
			$this->post_type             													// Post type
		);

	}

	/**
	 * HTML content of the Details Meta Box.
	 */
	public function details_html($post)
	{
		$accommodation_id = get_post_meta($post->ID, "_{$this->post_type}_accommodation_id", true);
		$season_id = get_post_meta($post->ID, "_{$this->post_type}_season_id", true);
		$num_nights = get_post_meta($post->ID, "_{$this->post_type}_num_nights", true);
		$total_price = get_post_meta($post->ID, "_{$this->post_type}_total_price", true);

		$accommodations = get_posts(['post_type' => 'obcal_accommodation', 'numberposts' => -1]);
		 
		$seasons = get_posts(['post_type' => 'obcal_season', 'numberposts' => -1]);

		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="<?= esc_attr($this->post_type . '_accommodation_id') ?>"><?php esc_html_e('Accommodation', 'open-booking-calendar-plus'); ?></label>
				</th>
				<td>
					<select name="<?= esc_attr($this->post_type . '_accommodation_id') ?>" id="<?= esc_attr($this->post_type . '_accommodation_id') ?>" >
						<?php
						foreach ($accommodations as $accommodation){
						?>
						<option value="<?= esc_attr($accommodation->ID) ?>" <?php selected( $accommodation->ID, $accommodation_id, true ); ?>>
							<?php echo esc_html( $accommodation->post_title ); ?>
						</option>
						<?php
						}						
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="<?= esc_attr($this->post_type . '_season_id') ?>"><?php esc_html_e('Season', 'open-booking-calendar-plus'); ?></label>
				</th>
				<td>
					<select name="<?= esc_attr($this->post_type . '_season_id') ?>" id="<?= esc_attr($this->post_type . '_season_id') ?>" >
						<?php
						foreach ($seasons as $season){
						?>
						<option value="<?= esc_attr($season->ID) ?>" <?php selected( $season->ID, $season_id, true ); ?>>
							<?php echo esc_html( $season->post_title ); ?>
						</option>
						<?php
						}						
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="<?= esc_attr($this->post_type . '_num_nights') ?>"><?php esc_html_e('Number of nights', 'open-booking-calendar-plus'); ?></label>
				</th>
				<td>
					<input type="number" name="<?= esc_attr($this->post_type . '_num_nights') ?>" id="<?= esc_attr($this->post_type . '_num_nights') ?>" value="<?= esc_attr($num_nights) ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="<?= esc_attr($this->post_type . '_total_price') ?>"><?php esc_html_e('Promotion price', 'open-booking-calendar-plus'); ?></label>
				</th>
				<td>
					$<input type="number" name="<?= esc_attr($this->post_type . '_total_price') ?>" id="<?= esc_attr($this->post_type . '_total_price') ?>" value="<?= esc_attr($total_price) ?>">
				</td>
			</tr>		
		</table>			
		<?php
	}

	/**
	 * Save data of Meta Boxes.
	 */
	public function save($post_id)
	{

		// Keys of the values to save directly
		$keys_to_save_directly = ['accommodation_id', 'season_id', 'num_nights', 'total_price'];

		/** 
		 * Sanitize POST values
		 */

		// Sanitize values for 'Save values in array directly'
		foreach ($keys_to_save_directly as $key_to_save) {
			if (array_key_exists("{$this->post_type}_{$key_to_save}", $_POST)) {
				if ($key_to_save == 'total_price') {
					$_POST["{$this->post_type}_{$key_to_save}"] = sanitize_text_field($_POST["{$this->post_type}_{$key_to_save}"]);
				} else {
					$_POST["{$this->post_type}_{$key_to_save}"] = sanitize_key($_POST["{$this->post_type}_{$key_to_save}"]);
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

				// Validate 'accommodation_id'

				// Validate 'season_id'

				// Validate 'num_nights'

				// Validate 'total_price'

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
			'accommodation' => esc_html__('Accommodation', 'open-booking-calendar-plus'),
			'season' => esc_html__('Season', 'open-booking-calendar-plus'),
			'num_nights' => esc_html__('Num. of nights', 'open-booking-calendar-plus'),
			'total_price' => esc_html__('Price', 'open-booking-calendar-plus'),
			'date' => $columns['date'],
		];
		return $new_columns;
	}

	/**
	 * Add Custom Columns Data to the Post Type Table.
	 */
	public function add_table_custom_values( $column, $post_id ) {
		switch ( $column ) {
		  case 'accommodation':
			$accommodation_id = get_post_meta( $post_id , "_{$this->post_type}_accommodation_id" , true );
			$accommodation = get_post($accommodation_id);
			echo esc_html($accommodation->post_title);
			break;
		case 'season':
			$season_id = get_post_meta( $post_id , "_{$this->post_type}_season_id" , true );
			$season = get_post($season_id);
			echo esc_html($season->post_title);
			break;
		case 'num_nights':
			echo esc_html(get_post_meta( $post_id , "_{$this->post_type}_num_nights" , true ));
			break;
		case 'total_price':
			echo "$" . esc_html(get_post_meta( $post_id , "_{$this->post_type}_total_price" , true ));
			break;
		}
	}

	/**
	 * Register the columns as sortable.
	 */
	public function register_table_sortable_columns( $columns ) {
		$columns['accommodation'] = 'Accommodation';
		$columns['season'] = 'Season';
		$columns['num_nights'] = 'Num Nights';
		$columns['total_price'] = 'Price';
		return $columns;
	}
}