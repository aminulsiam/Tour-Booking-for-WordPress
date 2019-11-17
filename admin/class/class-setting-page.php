<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class WtbmSettings {
	
	public function __construct() {
		$this->wtbm_settings();
	}
	
	public function wtbm_set_page_settings( $page_1_options, $page_2_options ) {
		$default = array(
			'panelGroup-10' => $page_1_options,
			'panelGroup-11' => $page_2_options,
		);
		
		return apply_filters( 'wtbm_settings_array', $default );
	}
	
	private function wtbm_settings() {
		
		
		$setting_options_1 = array(
			'page_nav'      => __( '<i class="far fa-bell"></i> Genarel', 'text-domain' ),
			'priority'      => 10,
			'page_settings' => array(
				
				'section_3' => array(
					'title'       => __( '', 'text-domain' ),
					'nav_title'   => __( '', 'text-domain' ),
					'description' => __( '', 'text-domain' ),
					'options'     => array(
						
						array(
							'id'          => 'wtbm_ticket_low_stock',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Low Stock Amount', 'text-domain' ),
							'details'     => __( 'Enter a amount when low stock will apper red in the dashboard', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Low Stock Amount', 'text-domain' ),
						),
					
					
					)
				),
			),
		);
		
		//$page_2_options = apply_filters( 'tour_pdf_settings', array() );
		
		$page_2_options = array(
			'page_nav'      => __( '<i class="fas fa-file-pdf"></i> PDF', 'text-domain' ),
			'priority'      => 10,
			'page_settings' => array(
				'section_20' => array(
					'title'       => __( 'PDF General Settings', 'text-domain' ),
					'nav_title'   => __( 'General', 'text-domain' ),
					'description' => __( 'This is section details', 'text-domain' ),
					'options'     => array(
						
						array(
							'id'          => 'pdf_logo',
							'title'       => __( 'Logo ', 'text-domain' ),
							'details'     => __( 'PDF Logo', 'text-domain' ),
							'placeholder' => 'https://i.imgur.com/GD3zKtz.png',
							'type'        => 'media',
						),
						
						array(
							'id'          => 'pdf_bacckground_image',
							'title'       => __( 'Background Image ', 'text-domain' ),
							'details'     => __( 'Select PDF Background Image', 'text-domain' ),
							'placeholder' => 'https://i.imgur.com/GD3zKtz.png',
							'type'        => 'media',
						),
						array(
							'id'      => 'pdf_backgroud_color',
							'title'   => __( 'PDF Background Color', 'text-domain' ),
							// 'details'	=> __('Description of colorpicker field','text-domain'),
							'default' => '#ffffff',
							'value'   => '#ffffff',
							'type'    => 'colorpicker',
						),
						array(
							'id'      => 'pdf_text_color',
							'title'   => __( 'PDF Text Color', 'text-domain' ),
							// 'details'	=> __('Description of colorpicker field','text-domain'),
							'default' => '#000000',
							'value'   => '#000000',
							'type'    => 'colorpicker',
						),
						array(
							'id'          => 'pdf_company_address',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Company Address', 'text-domain' ),
							'details'     => __( 'Enter your Company Address', 'text-domain' ),
							'type'        => 'textarea',
							'default'     => '',
							'placeholder' => __( 'Company Address', 'text-domain' ),
						),
						
						array(
							'id'          => 'pdf_company_phone',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Company Phone', 'text-domain' ),
							'details'     => __( 'Enter your Company Phone No', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Company phone', 'text-domain' ),
						),
						
						array(
							'id'          => 'pdf_company_email',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Company Email', 'text-domain' ),
							'details'     => __( 'Enter your Company Email', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Company Email', 'text-domain' ),
						),
						array(
							'id'          => 'pdf_terms_title',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Terms & Condition Title', 'text-domain' ),
							'details'     => __( 'Enter Terms & Condition Title', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Terms & Condition Title', 'text-domain' ),
						),
						array(
							'id'              => 'pdf_terms_text',
							'title'           => __( 'Terms & Condition Text', 'text-domain' ),
							'details'         => __( 'Terms & Condition Text', 'text-domain' ),
							'editor_settings' => array(
								'textarea_name' => 'pdf_terms_text_fields',
								'editor_height' => '150px'
							),
							'placeholder'     => __( 'Terms & Condition Text', 'text-domain' ),
							'default'         => '',
							'type'            => 'textarea',
						),
					)
				),
				
				'section_2' => array(
					'title'     => __( 'PDF Email Settings', 'text-domain' ),
					'nav_title' => __( 'Email Settings', 'text-domain' ),
					// 'description' 	=> __('This is section details','text-domain'),
					'options'   => array(
						array(
							'id'       => 'email_send_pdf',
							//'field_name'		    => 'text_multi_field',
							'title'    => __( 'Send Ticket', 'text-domain' ),
							'details'  => __( 'Send pdf to email?', 'text-domain' ),
							'default'  => 'yes',
							'value'    => 'yes',
							'multiple' => false,
							'type'     => 'select',
							'args'     => array(
								'yes' => __( 'Yes', 'text-domain' ),
								'no'  => __( 'No', 'text-domain' )
							),
						),
						
						array(
							'id'      => 'pdf_email_send_on',
							//'field_name'		    => 'text_multi_field',
							'title'   => __( 'Send Email on', 'text-domain' ),
							'details' => __( 'Send email with the ticket as attachment when these order status comes                            ', 'text-domain' ),
							// 'default'		=> array('option_3','option_2'),
							// 'value'		    => array('option_2'),
							'type'    => 'checkbox_multi',
							'args'    => array(
								'pending'    => __( 'Pending', 'text-domain' ),
								'processing' => __( 'Processing', 'text-domain' ),
								'completed'  => __( 'Completed', 'text-domain' ),
								'refunded'   => __( 'Refunded', 'text-domain' ),
								'cancelled'  => __( 'Cancelled', 'text-domain' ),
								'on-hold'    => __( 'On Hold', 'text-domain' ),
								'failed'     => __( 'Failed', 'text-domain' ),
							),
						),
						array(
							'id'          => 'pdf_email_subject',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Email Subject', 'text-domain' ),
							'details'     => __( 'Enter Email Subject', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Email Subject', 'text-domain' ),
						),
						array(
							'id'          => 'pdf_email_text',
							'title'       => __( 'Email Content', 'text-domain' ),
							'details'     => __( 'Email Content', 'text-domain' ),
							//'editor_settings'=>array('textarea_name'=>'wp_editor_field', 'editor_height'=>'150px'),
							'placeholder' => __( 'Email Content', 'text-domain' ),
							'default'     => '',
							'type'        => 'wp_editor',
						),
						array(
							'id'          => 'pdf_email_admin_notification_email',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Admin Notification Email', 'text-domain' ),
							'details'     => __( 'Admin Notification Email', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Admin Notification Email', 'text-domain' ),
						),
						array(
							'id'          => 'pdf_email_form_name',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Email From Name', 'text-domain' ),
							'details'     => __( 'Email From Name', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Email From Name', 'text-domain' ),
						),
						array(
							'id'          => 'pdf_email_form_email',
							//'field_name'		    => 'some_id_text_field_1',
							'title'       => __( 'Email From Email', 'text-domain' ),
							'details'     => __( 'Email From Email', 'text-domain' ),
							'type'        => 'text',
							'default'     => '',
							'placeholder' => __( 'Email From', 'text-domain' ),
						),
					)
				),
			),
		);
		
		
		$args         = array(
			'add_in_menu'     => true,
			'menu_type'       => 'sub',
			'menu_name'       => __( 'Tour Settings', 'text-domain' ),
			'menu_title'      => __( 'Tour Settings', 'text-domain' ),
			'page_title'      => __( 'Tour Settings', 'text-domain' ),
			'menu_page_title' => __( 'Tour Settings', 'text-domain' ),
			
			'capability'  => "manage_options",
			'cpt_menu'    => "edit.php?post_type=mage_tour",
			'menu_slug'   => "mage-tour-settings",
			'option_name' => "tour_manager_settings",
			'menu_icon'   => "dashicons-image-filter",
			
			'item_name'    => __( "Tour Booking Settings" ),
			'item_version' => "1.0.0",
			'panels'       => $this->wtbm_set_page_settings( $setting_options_1, $page_2_options ),
		);
		$AddThemePage = new AddThemePage( $args );
	}
}

new WtbmSettings();