<?php
/**
 * Plugin Name: FCWP Testimonials
 * Plugin URI: http://www.meetup.com/Fort-Collins-WordPress-Meetup
 * Description: A basic testimonials plugin built for the Fort Collins WordPress meetup
 * Version: 1.0.0
 * Author: Amber Hinds, David Hayes, Jeremy Green, Michael Launer, Mike Selander
 * Author URI: http://www.meetup.com/Fort-Collins-WordPress-Meetup
 * Text Domain: fcwp-testimonials
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Call in necessary files
require plugin_dir_path( __FILE__ ) . 'lib/posttype.php';
require plugin_dir_path( __FILE__ ) . 'lib/shortcode.php';
require plugin_dir_path( __FILE__ ) . 'lib/widget.php';
require plugin_dir_path( __FILE__ ) . 'lib/settings-page.php';

// Run our settings page
$testimonials_page = new FCWPSettingsPage;
$testimonials_page->hooks( __FILE__ );

// Get our option to create our media size
$options = get_option( 'fcwp_testimonials' );

//* Add the testimonals featured image size
if ( ! empty( $options['image_size'] ) ){
	add_image_size(
		'testimonial-featured', 		 // Media size slug
		$options['image_size']['width'], // Crop width
		$options['image_size']['width'], // Crop height
		true 							 // Hard crop
	);
}

// Enqueue our stylesheet IF chosen
add_action( 'wp_enqueue_scripts', 'fcwp_stylesheet' );
function fcwp_stylesheet() {

	$options = get_option( 'fcwp_testimonials' );
	if ( $options['toggle_styles'] ){
		wp_enqueue_style( 'fcwp-style', plugin_dir_url( __FILE__ ) . 'style.css' );
	}

}


/**
 * Activation method to initially setup our wp_option.
 *
 * Description.
 *
 * @see add_option, get_option
 */
register_activation_hook( __FILE__, 'fcwp_testimonials_activate' );
function myplugin_activate() {

	// Check if the current user has priveledges to run this method
	if ( ! current_user_can( 'activate_plugins' ) ){
        return;
    }

	// Check if this option has already been registered for some reason
	if ( get_option( 'fcwp_testimonials' ) ){
		return;
	}

	$value = array(
		'toggle_styles'	=> 1,
		'image_size'	=> array(
			'width'		=> 350,
			'height'	=> 350
		)
	);

	add_option(
		'fcwp_testimonials', 	// Value name
		$value, 				// Value we're pushing ing
		'', 					// Deprecated
		'no' 					// Autoload - generally put no
	);

}


/**
 * Dectivation method to clean up after ourselves.
 *
 * Description.
 *
 * @see unregister_setting, delete_option
 */
register_uninstall_hook( __FILE__, 'fcwp_testimonials_uninstall' );
function fcwp_testimonials_uninstall() {

	// Check if the current user has priveledges to run this method
	if ( ! current_user_can( 'activate_plugins' ) ){
        return;
    }

    // Check that we're on the correct file that is registered with the uninstall hook
    if ( __FILE__ != WP_UNINSTALL_PLUGIN ){
        return;
    }

	// Cleanly unregister our setting
	unregister_setting( 'fcwp_testimonials', 'fcwp_testimonials' );

	// Clean up our wp_options option
	delete_option( 'fcwp_testimonials' );

}