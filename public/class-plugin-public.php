<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * @package    Mage_Plugin
 * @subpackage Mage_Plugin/public
 * @author     MagePeople team <magepeopleteam@gmail.com>
 */
class Tour_Plugin_Public {
	
	private $plugin_name;
	
	private $version;
	
	public function __construct() {
		$this->load_public_dependencies();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_filter( 'single_template', array( $this, 'register_custom_single_template' ) );
		add_filter( 'template_include', array( $this, 'register_custom_tax_template' ) );
		
		//hotel option selected by this wp ajax hook for log in users
		add_action( 'wp_ajax_show_hotel_by_option_selected', array(
			$this,
			'show_hotel_by_option_selected'
		) );
		
		//hotel options selected by this wp ajax hook for non authentical users
		add_action( 'wp_ajax_nopriv_show_hotel_by_option_selected', array(
			$this,
			'show_hotel_by_option_selected'
		) );
		
		//this hook is fire when click on buy now
		add_action( 'wp_ajax_buy_tour_pakage', array( $this, 'buy_tour_pakage' ) );
		
	}
	
	
	private function load_public_dependencies() {
		require_once PLUGIN_DIR . 'public/shortcode/tour-shortcode.php';
	}
	
	/**
	 * Enqueue public styles
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( 'tour-jquery-ui-css', PLUGIN_URL . 'public/css/jquery-ui.css', array(), time(), 'all' );
		
		wp_enqueue_style( 'mage-tour-public-css', PLUGIN_URL . 'public/css/style.css', array(),
			time(), 'all' );
		
		wp_enqueue_style( 'magnific-pop-up', PLUGIN_URL . 'public/css/magnific.css', array(),
			time(), 'all' );
		
		wp_enqueue_style( 'fotoroma-styles', PLUGIN_URL . 'public/css/fotorama.css', array(),
			time(), 'all' );
		
		
	}//end method enqueue_styles
	
	
	/**
	 * This function is loaded scripts
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		
		wp_enqueue_script( 'tour-public-js', PLUGIN_URL . 'public/js/plugin-public.js', array(
			'jquery'
		), time(), true );
		
		$localzed_value = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'tour-public-js', 'woo_tour', $localzed_value );
		
		
		wp_enqueue_script( 'tour-public-js', PLUGIN_URL . 'public/js/plugin-public.js', array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-datepicker'
		), time(), true );
		
		wp_enqueue_script( 'magnific-pop-up-js', PLUGIN_URL . 'public/js/magnific.js',
			array( 'jquery' ), time(), true );
		
		wp_enqueue_script( 'pop-up-ui-js', PLUGIN_URL . 'public/js/pop-up-modal.js',
			array( 'jquery' ), time(), true );
		
		wp_enqueue_script( 'fotoroma-js', PLUGIN_URL . 'public/js/fotorama.js',
			array( 'jquery', 'jquery-ui-core' ), time(), true );
		
	}//end method enqueue_scripts
	
	/**
	 * @param $template
	 *
	 * @return string
	 */
	public function register_custom_single_template( $template ) {
		global $post;
		if ( $post->post_type == "mage_tour" ) {
			$template_name = 'single-tour.php';
			$template_path = 'mage-templates/';
			$default_path  = PLUGIN_DIR . 'public/templates/';
			$template      = locate_template( array( $template_path . $template_name ) );
			if ( ! $template ) :
				$template = $default_path . $template_name;
			endif;
			
			return $template;
		}
		
		return $template;
	}
	
	/**
	 * @param $template
	 *
	 * @return string
	 */
	public function register_custom_tax_template( $template ) {
		if ( is_tax( 'hotel_details' ) ) {
			$template = PLUGIN_DIR . 'public/templates/taxonomy-hotel_details.php';
		}
		
		return $template;
	}
	
	
	/**
	 * Show hotel details by selected option.
	 *
	 * This is ajax callback method for hotel selected options.
	 */
	public function show_hotel_by_option_selected() {
		
		$hotel_id = isset( $_POST['hotel_id'] ) ? $_POST['hotel_id'] : "";
		$tour_id  = isset( $_POST['tour_id'] ) ? $_POST['tour_id'] : 0;
		
		$room_details = maybe_unserialize( get_term_meta( $hotel_id, 'hotel_room_details', true ) );
		
		if ( ! is_array( $room_details ) ) {
			$room_details = array();
		}
		
		$tour_duration = get_post_meta( $tour_id, 'tour_duration', true );
		
		if ( empty( $tour_duration ) ) {
			$tour_duration = 1;
		}
		
		?>

        <table>
            <tr>
                <th><?php echo esc_html__( 'Room Type', 'woocommerce-tour-booking-manager' ) ?></th>
                <th><?php echo esc_html__( 'Room Fare', 'woocommerce-tour-booking-manager' ) ?></th>
                <th><?php echo esc_html__( 'Room Quantity', 'woocommerce-tour-booking-manager' ) ?></th>
            </tr>
			<?php
			
			foreach ( $room_details as $room ) {
				?>
                <tr>
                    <td>
                        <input type="hidden" name="room_name[]" value="<?php esc_html_e( $room['room_type'] ); ?>">
						<?php esc_html_e( $room['room_type'] ); ?>
                        <input type="hidden" name="room_cap[]"
                               value="<?php esc_html_e( $room['person_capacity'] ); ?>_<?php echo trim( $room['room_type'] ); ?>_<?php trim( esc_html_e( $room['room_fare'] ) ); ?>">

                    </td>

                    <td class="price-td">
                        <span style="display: none" class="room_price">
                            <?php esc_html_e( $room['room_fare'] * $tour_duration ); ?>
                        </span>
						
						<?php _e( '(Per Night -' . $room['room_fare'] . ' X ' . $tour_duration . ' Nights ) = ' . wc_price( $room['room_fare'] * $tour_duration ) ); ?>

                        <span class="person_capacity" style="display: none">

                            <?php esc_html_e( $room['person_capacity'] ); ?></span>

                        <input type="hidden" value="<?php esc_html_e( $room['room_fare'] ); ?>"
                               name="room_price[]" class="price">

                        <input type="hidden" value="<?php esc_html_e( $room['person_capacity'] ); ?>"
                               name="person_capacity" class="max_person"/>
                    </td>

                    <td>
                        <select value="" class="qty" name="room_qty[]">
                            <option value=0>0</option>
							<?php
							for ( $i = 1; $i <= $room['room_qty']; $i ++ ) {
								?>
                                <option value="<?php esc_attr_e( $i ); ?>"><?php esc_html_e( $i ) ?></option>
							<?php } ?>
                        </select>
                    </td>

                </tr>
			
			<?php } ?>

            <tr>
                <td colspan="2"><?php echo esc_html__( 'Total Person', 'woocommerce-tour-booking-manager' ); ?></td>

                <td align="right">
                    <input type="number" max="0" min="0" value="0" class="total_person" name="total_person"/>

                </td>

            </tr>

            <tr>
                <td colspan="2"><?php echo esc_html__( 'Total Price', 'woocommerce-tour-booking-manager' ); ?></td>
				
				<?php $currency_pos = get_option( 'woocommerce_currency_pos' ); ?>

                <td align="right">
					<?php
					if ( $currency_pos == "left" ) {
						echo get_woocommerce_currency_symbol();
					}
					?>
                    <span id="total" class="total">0</span>
					<?php
					if ( $currency_pos == "right" ) {
						echo get_woocommerce_currency_symbol();
					}
					?>
                </td>
            </tr>

            <tr>
                <td colspan="3">

                    <span class="form"></span>

                    <span class="error_text">
                        <?php echo esc_html__( 'No Traveller Selected',
	                        'woocommerce-tour-booking-manager' ); ?>
                    </span>

                    <button type="submit" class="btn btn-info pop_up_add_to_cart_button"
                            name="add-to-cart" disabled="disabled" value="<?php echo $tour_id; ?>">
						<?php
						echo esc_html__( 'Add To Cart', 'woocommerce-tour-booking-manager' );
						?>
                    </button>
                </td>

            </tr>
        </table>

        <script type="text/javascript">

            jQuery('.total_person').on('change', function () {
                var inputs = jQuery(this).val() || 0;
                var input = parseInt(inputs);
                var children = jQuery('.form > div').length || 0;

                if (input < children) {
                    jQuery('.form').empty();
                    children = 0;
                }

                for (var i = children + 1; i <= input; i++) {
                    jQuery('.form').append(
                        jQuery('<div/>')
                            .attr("id", "newDiv" + i)
                            .html('<?php do_action( 'attendee_form_builder', $tour_id ); ?>')
                    );
                }

            });

        </script>
		
		<?php
		
		exit();
	}//end method show_hotel_by_option_selected
	
	
}

new Tour_Plugin_Public();