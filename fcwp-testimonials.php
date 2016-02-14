<?php
/**
 * Plugin Name: FCWP Testimonials
 * Plugin URI: http://www.meetup.com/Fort-Collins-WordPress-Meetup
 * Description: A basic testimonials plugin built for the Fort Collins WordPress meetup
 * Version: 1.0.0
 * Author: Amber Hinds, David Hayes, Mike Selander, Jeremy Green, Michael Launer
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

// Get our option
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
