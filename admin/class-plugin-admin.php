<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * @package    Mage_Plugin
 * @subpackage Mage_Plugin/admin
 * @author     MagePeople team <magepeopleteam@gmail.com>
 */
class Tour_Plugin_Admin {
	
	private $plugin_name;
	
	private $version;
	
	public function __construct() {
		
		// $this->plugin_name = $plugin_name;
		// $this->version = $version;
		$this->load_admin_dependencies();
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'tour_booking_data_create' ) );
		add_action( 'woocommerce_order_status_changed', array( $this, 'change_attendee_status' ), 10, 4 );
		
		add_action( 'wp_trash_post', array( $this, 'wtbm_booking_info_trash' ), 90 );
		add_action( 'untrash_post', array( $this, 'wtbm_booking_info_untrash' ), 90 );
		
	}
	
	
	/**
	 * @param $post_id
	 */
	public function wtbm_booking_info_trash( $post_id ) {
	
		$post_type   = get_post_type( $post_id );
	
		if ( $post_type == 'shop_order' ) {
			$this->change_tour_booking_status( $post_id, 'trash', 'publish', 'trash' );
		}
	}
	
	/**
	 * @param $post_id
	 */
	public function wtbm_booking_info_untrash( $post_id ) {
		
		$post_type   = get_post_type( $post_id );
		
		if ( $post_type == 'shop_order' ) {
			$this->change_tour_booking_status( $post_id, 'publish', 'trash', 'processing' );
		}
	}
	
	
	/**
	 * @param $order_id
	 * @param $from_status
	 * @param $to_status
	 * @param $order
	 */
	public function change_attendee_status( $order_id, $from_status, $to_status, $order ) {
		
		global $wtbm, $wtbmfunctions;
		
		$order      = wc_get_order( $order_id );
		$order_meta = get_post_meta( $order_id );
		
		
		foreach ( $order->get_items() as $item_id => $item_values ) {
			
			$hotel_id = $this->wtbm_get_order_meta( $item_id, '_tour_id' );
			
			//check post types
			if ( get_post_type( $hotel_id ) == 'mage_tour' ) {
				
				if ( $order->has_status( 'processing' ) ) {
					
					$this->change_tour_booking_status( $order_id, 'publish', 'publish', 'processing' );
					
				}
				
				if ( $order->has_status( 'pending' ) ) {
					$this->change_tour_booking_status( $order_id, 'publish', 'publish', 'pending' );
				}
				
				if ( $order->has_status( 'on-hold' ) ) {
					$this->change_tour_booking_status( $order_id, 'publish', 'publish', 'on-hold' );
				}
				
				if ( $order->has_status( 'completed' ) ) {
					$this->change_tour_booking_status( $order_id, 'publish', 'publish', 'completed' );
				}
				
				if ( $order->has_status( 'cancelled' ) ) {
					$this->change_tour_booking_status( $order_id, 'publish', 'publish', 'cancelled' );
				}
				
				if ( $order->has_status( 'refunded' ) ) {
					$this->change_tour_booking_status( $order_id, 'publish', 'publish', 'refunded' );
				}
				
				if ( $order->has_status( 'failed' ) ) {
					$this->change_tour_booking_status( $order_id, 'publish', 'publish', 'failed' );
				}
				
			} //end of Post Type Check
		} //end order item foreach
	} //end method change_attendee_status
	
	
	/**
	 *
	 *
	 * @param $order_id
	 * @param $set_status
	 * @param $post_status
	 * @param $booking_status
	 */
	public function change_tour_booking_status( $order_id, $set_status, $post_status, $booking_status ) {
		$args = array(
			'post_type'      => array( 'mage_tour_booking' ),
			'posts_per_page' => - 1,
			'post_status'    => $post_status,
			'meta_query'     => array(
				array(
					'key'     => 'wtbm_order_id',
					'value'   => $order_id,
					'compare' => '='
				)
			)
		);
		
		$loop = new WP_Query( $args );
		foreach ( $loop->posts as $ticket ) {
			$post_id      = $ticket->ID;
			$current_post = get_post( $post_id, 'ARRAY_A' );
			update_post_meta( $post_id, 'wtbm_order_status', $booking_status );
			$current_post['post_status'] = $set_status;
			wp_update_post( $current_post );
		}
		
	}//end method change_tour_booking_status
	
	
	/**
	 *
	 *
	 * Get Order Itemdata value by Item id
	 *
	 * @param $item_id
	 * @param $key
	 *
	 * @return mixed
	 */
	public function wtbm_get_order_meta( $item_id, $key ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
		$sql        = 'SELECT meta_value FROM ' . $table_name . ' WHERE order_item_id =' . $item_id . ' AND meta_key="' . $key . '"';
		$results = $wpdb->get_results( $sql ) or die( mysql_error() );
		foreach ( $results as $result ) {
			$value = $result->meta_value;
		}
		
		return $value;
	}
	
	/**
	 * @param $order_id
	 */
	public function tour_booking_data_create( $order_id ) {
		
		if ( ! $order_id ) {
			return;
		}
		
		// Getting an instance of the order object
		$order        = wc_get_order( $order_id );
		$order_meta   = get_post_meta( $order_id );
		$order_status = $order->get_status();
		# Iterating through each order items (WC_Order_Item_Product objects in WC 3+)
		foreach ( $order->get_items() as $item_id => $item_values ) {
			$tour_id = $this->wtbm_get_order_meta( $item_id, '_tour_id' );
			
			if ( get_post_type( $tour_id ) == 'mage_tour' ) {
				
				$first_name      = isset( $order_meta['_billing_first_name'][0] ) ? $order_meta['_billing_first_name'][0] : array();
				$last_name       = isset( $order_meta['_billing_last_name'][0] ) ? $order_meta['_billing_last_name'][0] : array();
				$company_name    = isset( $order_meta['_billing_company'][0] ) ? $order_meta['_billing_company'][0] : array();
				$address_1       = isset( $order_meta['_billing_address_1'][0] ) ? $order_meta['_billing_address_1'][0] : array();
				$address_2       = isset( $order_meta['_billing_address_2'][0] ) ? $order_meta['_billing_address_2'][0] : array();
				$city            = isset( $order_meta['_billing_city'][0] ) ? $order_meta['_billing_city'][0] : array();
				$state           = isset( $order_meta['_billing_state'][0] ) ? $order_meta['_billing_state'][0] : array();
				$postcode        = isset( $order_meta['_billing_postcode'][0] ) ? $order_meta['_billing_postcode'][0] : array();
				$country         = isset( $order_meta['_billing_country'][0] ) ? $order_meta['_billing_country'][0] : array();
				$email           = isset( $order_meta['_billing_email'][0] ) ? $order_meta['_billing_email'][0] : array();
				$phone           = isset( $order_meta['_billing_phone'][0] ) ? $order_meta['_billing_phone'][0] : array();
				$billing_intotal = isset( $order_meta['_billing_address_index'][0] ) ? $order_meta['_billing_address_index'][0] : array();
				$payment_method  = isset( $order_meta['_payment_method_title'][0] ) ? $order_meta['_payment_method_title'][0] : array();
				$user_id         = isset( $order_meta['_customer_user'][0] ) ? $order_meta['_customer_user'][0] : array();
				$address         = $address_1 . ' ' . $address_2;
				
				$total_person     = $this->wtbm_get_order_meta( $item_id, 'Total Person' );
				$tour_start       = $this->wtbm_get_order_meta( $item_id, 'Tour Start Date' );
				$tour_end         = $this->wtbm_get_order_meta( $item_id, 'Tour End Date' );
				$hotel_name       = $this->wtbm_get_order_meta( $item_id, 'Hotel Name' );
				$tour_name        = $this->wtbm_get_order_meta( $item_id, 'Tour Name' );
				$tour_price       = $this->wtbm_get_order_meta( $item_id, '_tour_price' );
				$hotel_id         = $this->wtbm_get_order_meta( $item_id, '_hotel_id' );
				$tour_info        = maybe_unserialize( $this->wtbm_get_order_meta( $item_id, '_tour_info' ) );
				$user_info        = maybe_unserialize( $this->wtbm_get_order_meta( $item_id, '_tour_user_info' ) );
				$user_single_info = maybe_unserialize( $this->wtbm_get_order_meta( $item_id, '_hotel_user_single_info' ) );
				$hotel_info       = maybe_unserialize( $this->wtbm_get_order_meta( $item_id, '_hotel_info' ) );
				
				if ( is_array( $user_single_info ) && sizeof( $user_single_info ) > 0 ) {
					
					
					$reg_form_arr = maybe_unserialize( get_post_meta( $tour_id, 'attendee_reg_form', true ) );
					
					foreach ( $user_info as $users ) {
						
						# code...
						$title = '#' . $order_id . ' - ' . $tour_name;
						
						// ADD THE FORM INPUT TO $new_post ARRAY
						$new_post = array(
							'post_title'    => $title,
							'post_content'  => '',
							'post_category' => array(),
							// Usable for custom taxonomies too
							'tags_input'    => array(),
							'post_status'   => 'publish',
							// Choose: publish, preview, future, draft, etc.
							'post_type'     => 'mage_tour_booking'
							//'post',page' or use a custom post type if you want to
						);
						
						//SAVE THE POST
						$pid = wp_insert_post( $new_post );
						update_post_meta( $pid, 'wtbm_tour_id', $tour_id );
						update_post_meta( $pid, 'wtbm_hotel_id', $hotel_id );
						update_post_meta( $pid, 'wtbm_start', $tour_start );
						update_post_meta( $pid, 'wtbm_end', $tour_end );
						update_post_meta( $pid, 'wtbm_total_person', $total_person );
						update_post_meta( $pid, 'wtbm_order_id', $order_id );
						update_post_meta( $pid, 'wtbm_order_total', $tour_price );
						update_post_meta( $pid, 'wtbm_tour_info', $tour_info );
						update_post_meta( $pid, 'wtbm_hotel_info', $hotel_info );
						
						foreach ( $reg_form_arr as $reg_form ) {
							update_post_meta( $pid, $reg_form['field_id'], $users[ $reg_form['field_id'] ] );
						}
						
						update_post_meta( $pid, 'wtbm_billing_first_name', $first_name );
						update_post_meta( $pid, 'wtbm_billing_last_name', $last_name );
						update_post_meta( $pid, 'wtbm_billing_company', $company_name );
						update_post_meta( $pid, 'wtbm_billing_address', $address );
						update_post_meta( $pid, 'wtbm_billing_city', $city );
						update_post_meta( $pid, 'wtbm_billing_state', $state );
						update_post_meta( $pid, 'wtbm_billing_postcode', $postcode );
						update_post_meta( $pid, 'wtbm_billing_country', $country );
						update_post_meta( $pid, 'wtbm_billing_email', $email );
						update_post_meta( $pid, 'wtbm_billing_phone', $phone );
						
						update_post_meta( $pid, 'wtbm_billing_payment', $payment_method );
						update_post_meta( $pid, 'wtbm_user_id', $user_id );
						update_post_meta( $pid, 'wtbm_order_status', $order_status );
						
					} // end of ticket info loop
				} else {
					
					for ( $x = 0; $x <= $total_person; $x ++ ) {
						
						# code...
						$title = '#' . $order_id . ' - ' . $first_name . ' ' . $last_name;
						// ADD THE FORM INPUT TO $new_post ARRAY
						$new_post = array(
							'post_title'    => $title,
							'post_content'  => '',
							'post_category' => array(),
							// Usable for custom taxonomies too
							'tags_input'    => array(),
							'post_status'   => 'publish',
							// Choose: publish, preview, future, draft, etc.
							'post_type'     => 'mage_tour_booking'
							//'post',page' or use a custom post type if you want to
						);
						
						//SAVE THE POST
						$pid = wp_insert_post( $new_post );
						update_post_meta( $pid, 'wtbm_tour_id', $tour_id );
						update_post_meta( $pid, 'wtbm_hotel_id', $hotel_id );
						update_post_meta( $pid, 'wtbm_start', $tour_start );
						update_post_meta( $pid, 'wtbm_end', $tour_end );
						update_post_meta( $pid, 'wtbm_total_person', $total_person );
						update_post_meta( $pid, 'wtbm_order_id', $order_id );
						update_post_meta( $pid, 'wtbm_order_total', $tour_price );
						update_post_meta( $pid, 'wtbm_tour_info', $tour_info );
						update_post_meta( $pid, 'wtbm_hotel_info', $hotel_info );
						
						update_post_meta( $pid, 'wtbm_billing_first_name', $first_name );
						update_post_meta( $pid, 'wtbm_billing_last_name', $last_name );
						update_post_meta( $pid, 'wtbm_billing_company', $company_name );
						update_post_meta( $pid, 'wtbm_billing_address', $address );
						update_post_meta( $pid, 'wtbm_billing_city', $city );
						update_post_meta( $pid, 'wtbm_billing_state', $state );
						update_post_meta( $pid, 'wtbm_billing_postcode', $postcode );
						update_post_meta( $pid, 'wtbm_billing_country', $country );
						update_post_meta( $pid, 'wtbm_billing_email', $email );
						update_post_meta( $pid, 'wtbm_billing_phone', $phone );
						
						update_post_meta( $pid, 'wtbm_billing_payment', $payment_method );
						update_post_meta( $pid, 'wtbm_user_id', $user_id );
						update_post_meta( $pid, 'wtbm_order_status', $order_status );
						
					}
					
				}
			} // Ticket Post Type Check end
		} //Order Item data Loop
	} //End of the function
	
	/**
	 * Enqueue all styles
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'mage-jquery-ui-style', PLUGIN_URL . 'admin/css/jquery-ui.css', array() );
		
		wp_enqueue_style( 'pickplugins-options-framework', PLUGIN_URL . 'admin/assets/css/pickplugins-options-framework.css' );
		
		//wp_enqueue_style( 'wp-color-picker' );
		//wp_enqueue_style( 'wp-color-picker' );
		
		//wp_enqueue_style( 'jquery-ui', PLUGIN_URL . 'admin/assets/css/jquery-ui.css' );
		wp_enqueue_style( 'select2.min', PLUGIN_URL . 'admin/assets/css/select2.min.css' );
		wp_enqueue_style( 'codemirror', PLUGIN_URL . 'admin/assets/css/codemirror.css' );
		wp_enqueue_style( 'fontawesome', PLUGIN_URL . 'admin/assets/css/fontawesome.min.css' );
		wp_enqueue_style( 'mage-admin-css', PLUGIN_URL . 'admin/css/mage-plugin-admin.css', array(), time(), 'all' );
	}//end method enqueue_styles
	
	/**
	 * Enqueue all scripts
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( 'jquery' );
		
		wp_enqueue_script( 'jquery-ui-core' );
		
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		
		wp_enqueue_script( 'tour-magepeople-options-framework',
			PLUGIN_URL . 'admin/assets/js/pickplugins-options-framework.js', array( 'jquery' ) );
		
		wp_enqueue_script( 'select2.min', PLUGIN_URL . 'admin/assets/js/select2.min.js', array( 'jquery' ), time(), true );
		
		wp_enqueue_script( 'codemirror', PLUGIN_URL . 'admin/assets/js/codemirror.min.js', array( 'jquery' ), null, false );
		
		wp_enqueue_script( 'form-field-dependency', plugins_url( 'assets/js/form-field-dependency.js', __FILE__ ), array( 'jquery' ), time(), false );
		
		wp_enqueue_script( 'mage-tour-plugin-js', PLUGIN_URL . 'admin/js/plugin-admin.js', array(
			'jquery',
			'jquery-ui-core',
		), time(), true );
		
	}//end method enqueue_scripts
	
	
	private function load_admin_dependencies() {
		require_once PLUGIN_DIR . 'admin/class/class-create-cpt.php';
		require_once PLUGIN_DIR . 'admin/class/class-create-tax.php';
		require_once PLUGIN_DIR . 'admin/class/class-meta-box.php';
		require_once PLUGIN_DIR . 'admin/class/class-tax-meta.php';
		require_once PLUGIN_DIR . 'admin/class/class-export.php';
		require_once PLUGIN_DIR . 'admin/class/class-setting-page.php';
	}
	
	
}

new Tour_Plugin_Admin();


