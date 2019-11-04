<?php
get_header();
the_post();
?>

<div class="tour-content">
	<?php do_action( 'woocommerce_before_single_product' ); ?>

    <div class="tour-left-content">
		
		<?php
		$gallary_images = maybe_unserialize( get_post_meta( get_the_ID(), 'tour_gallary_image', true ) );
		
		if ( ! is_array( $gallary_images ) ) {
			$gallary_images = array();
		}
		
		?>
        <div class="fotorama" data-autoplay="5000" data-allowfullscreen="true" data-width="100%" data-height="auto">
			<?php
			foreach ( $gallary_images as $images ) {
				echo wp_get_attachment_image( $images, array( 1000, 800 ) );
			}
			?>
        </div>
        <section class="tour_title">
            <h1><?php the_title(); ?></h1>
        </section>

        <p><?php the_content(); ?></p>

        <section class="daywise_details">
            <h6>
				<?php echo esc_html__( 'More Details', 'woocommerce-tour-booking-manager' ); ?>
            </h6>
			<?php
			$day_details = maybe_unserialize( get_post_meta( $post->ID, 'more_details', true ) );
			
			if ( ! is_array( $day_details ) ) {
				$day_details = array();
			}
			
			foreach ( $day_details as $day_detail ) {
				?>
                <button class="accordion">
					<?php echo esc_html( ucfirst( $day_detail['details_topic'] ) ); ?>
                </button>
                <div class="panel">
                    <p><?php echo esc_html( ucfirst( $day_detail['details'] ) ); ?></p>
                </div>
			<?php } ?>
        </section>
    </div>

    <div class="tour-right-content">
		
		<?php
		if ( isset( $_POST['submit_tour'] ) ) {
			$tour_id               = get_the_id();
			$tour_date             = $_POST['tour_date'];
			$tour_hotel            = $_POST['tour_hotel'];
			$tour_hotel_room_name  = $_POST['room_name'];
			$tour_hotel_room_price = $_POST['room_price'];
			$tour_hotel_room_qty   = $_POST['room_qty'];
			
			$total_room  = count( $tour_hotel_room_name );
			$total_price = 0;
			for ( $i = 0; $i < $total_room; $i ++ ) {
				$room_qty = $tour_hotel_room_qty[ $i ];
				if ( $room_qty > 0 ) {
					$hotel[ $i ]['tour_id'] = stripslashes( strip_tags( $tour_id ) );
					
					$per_ticket_price = $tour_hotel_room_price[ $i ];
					$net_ticket_price = $per_ticket_price * $room_qty;
					
					$hotel[ $i ]['hotel_id']   = stripslashes( strip_tags( $tour_hotel ) );
					$hotel[ $i ]['room_name']  = stripslashes( strip_tags( $tour_hotel_room_name[ $i ] ) );
					$hotel[ $i ]['room_price'] = stripslashes( strip_tags( $tour_hotel_room_price[ $i ] ) );
					$hotel[ $i ]['room_qty']   = stripslashes( strip_tags( $tour_hotel_room_qty[ $i ] ) );
					$total_price               = $total_price + $net_ticket_price;
				}
			}
			
			echo $total_price;
		}
		?>
		
		<?php
		
		$get_hotel_details = get_terms( array(
			'taxonomy'   => 'hotel_details',
			'hide_empty' => false,
		) );
		
		?>

        <div class="tour_destination">
			<?php
			
			$display_google_map = get_post_meta( $post->ID, 'google_map_display', true );
			
			$destinations = get_the_terms( $post->ID, 'destination' );
			
			if ( ! is_array( $destinations ) ) {
				$destinations = array();
			}
			
			foreach ( $destinations as $destination ) {
				echo "<h3><span class='dashicons dashicons-location'></span>" . esc_html__( 'Tour Destination : ', 'woocommerce-tour-booking-manager' ) . esc_html( ucfirst( $destination->name ) ) . "</h3>";
				
				if ( "on" == $display_google_map ) {
					?>
                    <iframe id="gmap_canvas"
                            src="https://maps.google.com/maps?q=<?php esc_html_e( $destination->name ); ?>&amp;t=&amp;z=10&amp;ie=UTF8&amp;iwloc=&amp;output=embed"
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                            style="width: 100%;min-height: 250px;"></iframe>
				<?php }
			} ?>
        </div>

        <div class="Module-options">
            <div class="Module-option">
                <div class="text">

                    <div class="tour_offer_type">
						<?php
						global $post;
						
						$tour_offer_type = get_the_terms( $post->ID, 'tour_offer_type' );
						
						$tentative_start_date = get_post_meta( $post->ID, 'start_date', true );
						$tentative_end_date   = get_post_meta( $post->ID, 'end_date', true );
						
						if ( ! is_array( $tour_offer_type ) ) {
							$tour_offer_type = array();
						}
						
						foreach ( $tour_offer_type as $type ) {
							if ( 'fixed' == $type->name ) {
								echo "<div class='title'><h6>" . esc_html__( 'Departure Time : ', 'woocommerce-tour-booking-manager' ) . esc_html( ucfirst( $type->name ) ) . "<span class='dashicons dashicons-lock'></span></h6></div>";
							} else {
								echo "<div class='title'><h6>" . esc_html__( 'Departure Time : ', 'woocommerce-tour-booking-manager' ) . esc_html( ucfirst( $type->name ) ) . "<span class='dashicons dashicons-unlock'></span></h6></div>";
								
								?>
								<?php
							}
						}
						?>
                    </div>


                    <div class="validities">
						
						<?php
						
						$valid_form_date_format = date_i18n( "d", strtotime(
							$tentative_start_date ) );
						
						$valid_form_month_year_format = date_i18n( "M'y", strtotime(
							$tentative_start_date ) );
						
						$valid_till_date_format = date_i18n( "d", strtotime(
							$tentative_end_date ) );
						
						$valid_till_month_year_format = date_i18n( "M'y", strtotime(
							$tentative_end_date ) );
						
						?>

                        <div class="validity"><span><?php echo esc_html__( 'Valid From', 'woocommerce-tour-booking-manager' );
								?></span>
                            <p><span><?php esc_html_e( $valid_form_date_format ); ?></span>
                                <small><?php esc_html_e( $valid_form_month_year_format ); ?></small>
                            </p>
                        </div>
                        <div class="validity"><span><?php echo esc_html__( 'Valid Till', 'woocommerce-tour-booking-manager' )
								?></span>
                            <p><span><?php esc_html_e( $valid_till_date_format ); ?></span>
                                <small><?php esc_html_e( $valid_till_month_year_format ); ?></small>
                            </p>
                        </div>
                        <div class="validity"><span>Departs</span>
                            <p>
                                <small class="price">EVERY DAY</small>
                            </p>
                        </div>
                    </div>
                    <div class="row-block">
						
						<?php
						$hotel_room_fares = get_post_meta( $post->ID, 'hotel_room_details',
							true );
						
						
						$get_hotel_fares = maybe_unserialize( $hotel_room_fares );
						
						if ( ! is_array( $get_hotel_fares ) ) {
							$get_hotel_fares = array();
						}
						
						foreach ( $get_hotel_fares as $room_fares ) {
							?>
                            <div class="column">
                                <span><?php esc_html_e( '' . ucfirst( $room_fares['room_type'] ) . ' ', '' ) ?></span><strong><span
                                            class="room_fare"><?php echo wc_price( $room_fares['room_fare'] );
										?></span></strong>
                            </div>
						<?php } ?>

                        <div class="column full">
                            <div class="hotel-list">
								
								<?php
								foreach ( $get_hotel_details as $hotel_name ) {
									?>
                                    <div class="item">
                                        <span class="dashicons dashicons-admin-home"></span>
                                        <span class="hotel_name"><?php _e( ucfirst( $hotel_name->name )
											); ?></span>
                                    </div>
								<?php } ?>
                            </div>
                        </div>
                    </div>
					
					<?php
					echo Tour_Booking_Helper::hotel_details( $get_hotel_details );
					?>

                </div>
            </div>
        </div>
    </div>
	<?php
	$tour_start_date = get_post_meta( $post->ID, 'start_date', true );
	$tour_end_date   = get_post_meta( $post->ID, 'end_date', true );
	
	$tour_start_year  = date( 'Y', strtotime( $tour_start_date ) );
	$tour_start_month = date( 'm', strtotime( $tour_start_date ) );
	$tour_start_day   = date( 'd', strtotime( $tour_start_date ) );
	
	$tour_end_year  = date( 'Y', strtotime( $tour_end_date ) );
	$tour_end_month = date( 'm', strtotime( $tour_end_date ) );
	$tour_end_day   = date( 'd', strtotime( $tour_end_date ) );
	?>

</div>

<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<script type="text/javascript">
    jQuery('.add_to_cart').hide();
    jQuery(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: new Date(<?php _e( $tour_start_year ); ?>, <?php _e( $tour_start_month ); ?> -1, <?php _e( $tour_start_day ); ?>),
        maxDate: new Date(<?php _e( $tour_end_year ); ?>, <?php _e( $tour_end_month ); ?> -1, <?php _e(
			$tour_end_day ); ?>)
    });
</script>
<?php get_footer(); ?>


