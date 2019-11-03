<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

class Tour_Tax {
	public function __construct() {
		add_action( "init", array( $this, "mage_tax_init" ), 5 );
	}

	/**
	 * Register taxonomy
	 */
	public function mage_tax_init() {
		$labels = array(
			'singular_name' => _x( 'Hotel', '' ),
			'name'          => _x( 'Hotel', '' ),
			'search_items'      => __( 'Search Hotel', 'textdomain' ),
			'all_items'         => __( 'All Hotel', 'textdomain' ),
			'parent_item'       => __( 'Parent Hotel', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Hotel:', 'textdomain' ),
			'edit_item'         => __( 'Edit Hotel', 'textdomain' ),
			'update_item'       => __( 'Update Hotel', 'textdomain' ),
			'add_new_item'      => __( 'Add New Hotel', 'textdomain' ),
			'new_item_name'     => __( 'New Hotel Name', 'textdomain' ),
			'menu_name'         => __( 'Hotel', 'textdomain' ),			
		);

		$args = array(
			'hierarchical'          => true,
			"public"                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'tour-hotel' ),
		);
		register_taxonomy( 'hotel_details', 'mage_tour', $args );

		$labels = array(
			'singular_name' => _x( 'Hotel Type', '' ),
			'name'          => _x( 'Hotel Types', '' ),
		);

		$args = array(
			'hierarchical'          => true,
			"public"                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'tour-hotel-type' ),
		);
		register_taxonomy( 'hotel_type', 'mage_tour', $args );

		$labels = array(
			'singular_name' => _x( 'Tour Location', '' ),
			'name'          => _x( 'Tour Location', '' ),
		);

		$args = array(
			'hierarchical'          => true,
			"public"                => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'tour-location' ),
		);
		register_taxonomy( 'destination', 'mage_tour', $args );


	}
}
new Tour_Tax();