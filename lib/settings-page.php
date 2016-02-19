<?php

class FCWPSettingsPage{

	/**
	 * Hooks function to add to.
	 *
	 * This function is simply a method to run all our WordPress hooks. We use this
	 * in lieu of a __construct method to avoid the hooks being registered and
	 * run twice in the instance of someone accessing a single method.
	 *
	 * @see add_action	https://developer.wordpress.org/reference/functions/add_action/
	 * @see add_filter	https://developer.wordpress.org/reference/functions/add_filter/
	 */
	public function hooks( $file ){

		add_action( 'admin_menu', array( $this, 'set_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_fields' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( $file ) , array( $this, 'add_settings_link' ) );

	}

	/**
	 * Add and initiate our submenu for options.
	 *
	 * Her's where we create our actual settings page. Using one simple function,
	 * we name the page, set a title, callback function, id and givt it a minimum
	 * capability of user who can access it.
	 *
	 * One could also set a menu_page but this should really only been done if
	 * the plugin is big enough to support multiple sub-settings pages.
	 *
	 * @see add_submenu_page	https://developer.wordpress.org/reference/functions/add_submenu_page/
	 */
	public function set_menu_page(){

		add_submenu_page(
    		'options-general.php',								// The page that we're creating a
    		__( 'Testimonial Settings', 'fcwp-testimonials' ),	// Page title text
    		__( 'Testimonial Settings', 'fcwp-testimonials' ),	// Menu title text
    		'manage_options',									// Minimum capability to see this page
    		'fcwp_page',										// Menu slug (ID)
    		array( $this, 'settings_page' )						// Callback function to render the page
    	);

	}


	/**
	 * Add settings link to plugin list table.
	 *
	 * Modify the links in our plugin listing (plugins/php) to display a 'settings'
	 * link. Link to the settings page we set in this file.
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
	 * Here's where we actually register each and every settings and section.
	 * The setting is the encompassing group of settings and then each setting
	 * field is an individual setting item.
	 *
	 * These can also be hooked into any existing settings pags but it's most
	 * common to create your own so that it's easy for a user to find.
	 *
	 * @see add_settings_section	https://developer.wordpress.org/reference/functions/add_settings_section/
	 * @see add_settings_field		https://developer.wordpress.org/reference/functions/add_settings_field/
	 * @see register_setting		https://developer.wordpress.org/reference/functions/register_setting/
	 */
	public function register_fields(){

		// Add settings section
	    add_settings_section(
	        'fcwp_group',							// ID used to identify this section and with which to register options
	        __( 'Testimonial Display Options', 'fcwp-testimonials' ), // Title to be displayed on the administration page
	        array( $this, 'section_description' ),	// Callback used to render the description of the section
	        'fcwp_page'								// Page on which to add this section of options
	    );


		// Add styles field
		add_settings_field(
		    'toggle_styles',					// ID used to identify the field throughout the theme
		    __( 'Use FCWP Styles?', 'fcwp-testimonials' ), // The field label
		    array( $this, 'styles_field' ),		// Callback used to render the HTML of the field
		    'fcwp_page',						// The page on which this option will be displayed
		    'fcwp_group'						// The name of the section to which this field belongs
		);


		// Add image field
		add_settings_field(
		    'image_size',						// ID used to identify the field throughout the theme
		    __( 'Enter image size', 'fcwp-testimonials' ), // The field label
		    array( $this, 'image_field' ),		// Callback used to render the HTML of the field
		    'fcwp_page',						// The page on which this option will be displayed
		    'fcwp_group'						// The name of the section to which this field belongs
		);

		register_setting(
			'fcwp_group', 			// The group of settings that this setting belongs to
			'fcwp_option' 			// The setting ID
		);

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
	 * Here we compile the HTML for the styles toggle field. Simple checkbox.
	 *
	 * @see checked		https://developer.wordpress.org/reference/functions/checked/
	 * @see get_option	https://developer.wordpress.org/reference/functions/get_option/
	 */
	public function styles_field(){

		$html = '';
		$options = get_option( 'fcwp_option' );

		if ( isset( $options['toggle_styles'] ) ){
			$checked = checked( $options['toggle_styles'], 1, false );
		} else {
			$checked = '';
		}

		$html .= "<input type='checkbox' name='fcwp_option[toggle_styles]' $checked value='1'> ";
		$html .= "<label for='fcwp_option[toggle_styles]'>".__( 'Yes, use the plugin styles', 'fcwp-testimonials' )."</label>";

		echo $html;

	}


	/**
	 * Retrieve our setting and print out the input for the image dimensions.
	 *
	 * Here we compile the HTML for the styles toggle field. Two boxes with the
	 * height and width broken into sub array values.
	 *
	 * @see get_option	https://developer.wordpress.org/reference/functions/get_option/
	 */
	public function image_field(){

		$html = '';
		$options = get_option( 'fcwp_option' );

		if ( empty( $options['image_size'] ) ){
			$options['image_size']['width']		= '350';
			$options['image_size']['height']	= '350';
		}

		$html .= "<input type='text' name='fcwp_option[image_size][width]' value='".$options['image_size']['width']."'>";
		$html .= "<input type='text' name='fcwp_option[image_size][height]' value='".$options['image_size']['height']."'>";

		echo $html;

	}


	/**
	 * Build our settings page.
	 *
	 * This function serves to cimpile all of the pieces for the settings page.
	 * It seems so simple because we have registered our settings with the Settings
	 * API which does the heavy lifting for us. We could have put our HTML fields
	 * in here and done our own checks instead, but the Settings API is cleaner
	 * and generally safer.
	 *
	 * @see settings_fields			https://developer.wordpress.org/reference/functions/settings_fields/
	 * @see do_settings_sections 	https://developer.wordpress.org/reference/functions/do_settings_sections/
	 * @see submit_button			https://developer.wordpress.org/reference/functions/submit_button/
	 */
	public function settings_page(){

		?>
		<form action='options.php' method='post'>

			<?php
				settings_fields( 'fcwp_group' );				// Call our fields for this page
				do_settings_sections( 'fcwp_page' );			// Print out the section
				submit_button();								// Form submit button
			?>

		</form>
		<?php

	}

}