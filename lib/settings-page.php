<?php

class FCWPSettingsPage(){

	public function hooks(){

		add_action( 'admin_menu', array( $this, 'set_menu_page' ) );

	}


	public function set_menu_page(){

		add_submenu_page(
    		'options-general.php',								// The page that we're creating a
    		__( 'Testimonial Settings', 'fcwp-testimonials' ),	// Page title text
    		__( 'Testimonial Settings', 'fcwp-testimonials' ),	// Menu title text
    		'manage_options',									// Minimum capability to see this page
    		'op_settings',										// Menu slug (ID)
    		array( $this, 'op_settings_page' )					// Callback function to run the page under
    	);

	}


	public function register_fields(){



	}


	public function settings_page(){



	}

}