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
    		array( $this, 'settings_page' )						// Callback function to render the page
    	);

	}


	public function register_fields(){

		register_setting( 'fcwp_testimonials', 'plugin_prefix_settings' );

		// Add settings section
	    add_settings_section(
	        'fcwp_testimonials',					// ID used to identify this section and with which to register options
	        __( 'Testimonial Display Options', 'fcwp-testimonials' ), // Title to be displayed on the administration page
	        array( $this, 'section_description' ),	// Callback used to render the description of the section
	        'fcwp_testimonials'						// Page on which to add this section of options
	    );


		// Add styles field
		add_settings_field(
		    'toggle_styles',					// ID used to identify the field throughout the theme
		    __( 'Use FCWP Styles?', 'fcwp-testimonials' ), // The field label
		    array( $this, 'styles_field' ),		// Callback used to render the HTML of the field
		    'fcwp_testimonials',				// The page on which this option will be displayed
		    'fcwp_testimonials'					// The name of the section to which this field belongs
		);


		// Add image field
		add_settings_field(
		    'image_size',						// ID used to identify the field throughout the theme
		    __( 'Enter image size', 'fcwp-testimonials' ), // The field label
		    array( $this, 'image_field' ),		// Callback used to render the HTML of the field
		    'fcwp_testimonials',				// The page on which this option will be displayed
		    'fcwp_testimonials'					// The name of the section to which this field belongs
		);

	}


	public function section_description(){

		echo __( 'Control the the frontend output of your testimonials. You can use these settings to modify how your testimonials appear.', 'fcwp-testimonials' );

	}


	public function styles_field(){

		$html = '';
		$options = get_option( 'fcwp_testimonials' );

		$html .= "<input type='checkbox' name='plugin_prefix_settings[toggle_styles]' ".checked( $options['toggle_styles'], 1 )." value='1'> ";
		$html .= "<label for='show_header'>".__( 'Use plugin stylesheet', 'fcwp-testimonials' )."</label>";

		echo $html;

	}


	public function image_field(){

		$options = get_option( 'fcwp_testimonials' );

		if ( empty( $options['image_size'] ) ){
			$options['image_size']['width']		= '350';
			$options['image_size']['height']	= '350';
		}

		?>
		<input type='text' name='plugin_prefix_settings[image_size][width]' value='<?php echo $options['image_size']['width']; ?>'>
		<input type='text' name='plugin_prefix_settings[image_size][height]' value='<?php echo $options['image_size']['height']; ?>'>
		<?php

	}


	public function settings_page(){

		?>
		<form action='options.php' method='post'>

			<?php
				settings_fields( 'fcwp_testimonials' );			// Call our fields for this page
				do_settings_sections( 'fcwp_testimonials' );
				submit_button();								// Form submit button
			?>

		</form>
		<?php

	}

}

$testimonials_page = new FCWPSettingsPage;
$testimonials_page->hooks();