<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

if ( ! class_exists( 'Woo_Tour_Shortcode' ) ) {
	class Woo_Tour_Shortcode {
		
		public function __construct() {
			add_action( 'init', array( $this, 'woo_tour_shortcode' ) );
		}
		
		/**
		 * Woo Tour Shortcode
		 *
		 * All woo tour shortcode are written in this method.
		 */
		public function woo_tour_shortcode() {
			
			//this shortcode is showing all tour pakage in a page
			add_shortcode( 'woo_tour_pakage_page', array( $this, 'woo_tour_pakage_page' ) );
			
			//this shortcode is for search tour pakage
			add_shortcode( 'woo_tour_search', array( $this, 'woo_tour_search' ) );
			
		}//end method woo_tour_shortcode
		
		
		/**
		 * shortcode callback [woo_tour_search]
		 *
		 * @param $atts
		 *
		 * @return string|void
		 */
		public function woo_tour_search( $atts ) {
			
			//get all taxonomy
			$terms_slug = get_terms( array(
				'taxonomy'   => 'destination',
				'hide_empty' => false,
			) );
			
			$atts = shortcode_atts(
				
				array(
					'view' => 'no list',
				), $atts
			);
			
			$get_value = isset( $_GET['search'] ) ? $_GET['search'] : "";
			$get_date  = isset( $_GET['search_date'] ) ? $_GET['search_date'] : "";
			
			?>
            <div class="search_box">
                <h1><?php echo esc_html__( "Search Tour Pakage",
						'woocommerce-tour-booking-manager' ); ?></h1>

                <form class="example" action="<?php echo site_url(); ?>/woo-tour-list/">

                    <input type="text"
                           placeholder="<?php echo esc_attr__( 'Search Your likable Tour pakage......',
						       '' ); ?>" name="search" class="search" value="<?php echo $get_value;
					?>" required/>

                    <input type="text" class="search_date" name="search_date" placeholder="Enter date"
                           value="<?php echo $get_date; ?>"/>

                    <button type="submit"><i class="fa fa-search"></i></button>

                </form>
            </div>
			<?php
			
			$slugs = array();
			foreach ( $terms_slug as $slug ) {
				$slugs[] = $slug->name;
			}
			
			$localzed_value = array(
				'pakages' => array_unique( $slugs )
			);
			
			wp_localize_script( 'tour-public-js', 'woo_tour', $localzed_value );
			
			if ( isset( $_GET['search'] ) && ! empty( $_GET['search'] ) ) {
				
				$searching_pakages = $_GET['search'];
				$searching_date    = $_GET['search_date'];
				
				$args = array(
					'post_type' => 'mage_tour',
					
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'end_date',
							'value'   => $searching_date,
							'compare' => '>=',
							'field'   => 'end_date',
						),
					),
					
					'tax_query'      => array(
						array(
							'taxonomy' => 'destination',
							'terms'    => $searching_pakages,
							'field'    => 'slug'
						),
					),
					'posts_per_page' => - 1,
				);
				
				$get_searching_pakages = get_posts( $args );
				
				$slugs = array();
				foreach ( $terms_slug as $slug ) {
					$slugs[] = $slug->name;
				}
				
				$localzed_value = array(
					'pakages' => array_unique( $slugs )
				);
				
				wp_localize_script( 'tour-public-js', 'woo_tour', $localzed_value );
				
				$get_tour_pakages = Tour_Booking_Helper::search_and_all_tour_pakage( $get_searching_pakages,
					$atts );
				
				if ( $get_tour_pakages != "" ) {
					return $get_tour_pakages;
				}
				
			}//end if condition
		}//end method woo_tour_search
		
		/**
		 * Callback Shortcode[woo_tour_pakage_page]
		 */
		public function woo_tour_pakage_page( $atts ) {
			
			$atts = shortcode_atts(
				
				array(
					'view' => 'no list',
				), $atts
			);
			
			return Tour_Booking_Helper::All_Pakage_Page( $atts );
			
		}//end method woo_tour_pakage_page
		
		
	}//end Plugin_Shortcode class
}//end if class exist block

new Woo_Tour_Shortcode();