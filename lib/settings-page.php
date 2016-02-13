<?php

class FCWPSettingsPage{


	public function hooks(){

		add_action( 'admin_menu', array( $this, 'set_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_fields' ) );

	}


	public function set_menu_page(){

		add_submenu_page(
    		'options-general.php',								// The page that we're creating a
    		__( 'Testimonial Settings', 'fcwp-testimonials' ),	// Page title text
    		__( 'Testimonial Settings', 'fcwp-testimonials' ),	// Menu title text
    		'manage_options',									// Minimum capability to see this page
    		'fcwp_testimonials',								// Menu slug (ID)
    		array( $this, 'op_settings_page' )					// Callback function to render the page
    	);

	}


	public function register_fields(){

		// Add settings section
	    add_settings_section(
	        'fcwp_testimonials',					// ID used to identify this section and with which to register options
	        'Testimonial Options',					// Title to be displayed on the administration page
	        array( $this, 'section_description' ),	// Callback used to render the description of the section
	        'options-general.php?page=fcwp_testimonials' // Page on which to add this section of options
	    );


		// Add styles field
		add_settings_field(
		    'toggle_styles',					// ID used to identify the field throughout the theme
		    'Use FCWP Styles?',					// The field label
		    array( $this, 'styles_field' ),		// Callback used to render the HTML of the field
		    'options-general.php?page=fcwp_testimonials',	// The page on which this option will be displayed
		    'fcwp_testimonials'					// The name of the section to which this field belongs
		);


		// Add image field
		add_settings_field(
		    'image_size',						// ID used to identify the field throughout the theme
		    'Enter image size',					// The field label
		    array( $this, 'image_field' ),		// Callback used to render the HTML of the field
		    'options-general.php?page=fcwp_testimonials',	// The page on which this option will be displayed
		    'fcwp_testimonials'					// The name of the section to which this field belongs
		);

	}


	public function settings_page(){



	}

}

$testimonials_page = new FCWPSettingsPage;
$testimonials_page->hooks();