<?php

class FCWPSettingsPage{

	/**
	 * Hooks function to add to.
	 *
	 * Description.
	 *
	 * @see add_action
	 */
	public function hooks( $file ){

		add_action( 'admin_menu', array( $this, 'set_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_fields' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( $file ) , array( $this, 'add_settings_link' ) );

	}

	/**
	 * Add and initiate our submenu for options.
	 *
	 * Description.
	 *
	 * @see add_submenu_page
	 */
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


	/**
	 * Add settings link to plugin list table.
	 *
	 * Description.
	 *
	 * @param  array $links Existing links
	 * @return array Modified links
	 */
	public function add_settings_link( $links ) {

		$settings_link = '<a href="options-general.php?page=fcwp_testimonials">' . __( 'Settings', 'fcwp-testimonials' ) . '</a>';
  		array_push( $links, $settings_link );

  		return $links;

	}


	/**
	 * Initiate the sections and settings fields for the Settings API.
	 *
	 * Description.
	 *
	 * @see add_settings_section, add_settings_field
	 */
	public function register_fields(){

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

		register_setting( 'fcwp_testimonials', 'fcwp_testimonials' );

	}


	/**
	 * Print the section description.
	 */
	public function section_description(){

		echo __( 'Control the the frontend output of your testimonials. You can use these settings to modify how your testimonials appear.', 'fcwp-testimonials' );

	}


	/**
	 * Retrieve our setting and print out the input for the styles toggle.
	 *
	 * Description.
	 *
	 * @see get_option
	 */
	public function styles_field(){

		$html = '';
		$options = get_option( 'fcwp_testimonials' );

		if ( isset( $options['toggle_styles'] ) ){
			$checked = checked( $options['toggle_styles'], 1, false );
		} else {
			$checked = '';
		}

		$html .= "<input type='checkbox' name='fcwp_testimonials[toggle_styles]' $checked value='1'> ";
		$html .= "<label for='fcwp_testimonials[toggle_styles]'>".__( 'Yes, use the plugin styles', 'fcwp-testimonials' )."</label>";

		echo $html;

	}


	/**
	 * Retrieve our setting and print out the input for the image dimensions.
	 *
	 * Description.
	 *
	 * @see get_option
	 */
	public function image_field(){

		$html = '';
		$options = get_option( 'fcwp_testimonials' );

		if ( empty( $options['image_size'] ) ){
			$options['image_size']['width']		= '350';
			$options['image_size']['height']	= '350';
		}

		$html .= "<input type='text' name='fcwp_testimonials[image_size][width]' value='".$options['image_size']['width']."'>";
		$html .= "<input type='text' name='fcwp_testimonials[image_size][height]' value='".$options['image_size']['height']."'>";

		echo $html;

	}


	/**
	 * Build our settings page.
	 *
	 * Description.
	 *
	 * @see settings_fields, do_settings_sections, submit_button
	 */
	public function settings_page(){

		?>
		<form action='options.php' method='post'>

			<?php
				settings_fields( 'fcwp_testimonials' );			// Call our fields for this page
				do_settings_sections( 'fcwp_testimonials' );	// Print out the section
				submit_button();								// Form submit button
			?>

		</form>
		<?php

	}

}