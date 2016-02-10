<?php
/**
 * Plugin Name: FCWP Testimonials
 * Plugin URI: http://www.meetup.com/Fort-Collins-WordPress-Meetup
 * Description: A basic testimonials plugin built for the Fort Collins WordPress meetup
 * Version: 1.0.0
 * Author: Amber Hinds
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

// add stuff here

//* Add the testimonals featured image size
add_image_size( 'testimonial-featured', 350, 350, true ); // 350 pixels wide by 350 pixels tall, hard crop mode