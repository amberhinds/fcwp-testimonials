<?php

add_shortcode( 'fcwp_testimonial', 'fcwp_shortcode' );
function fcwp_shortcode() {
	$args = array(
		'post_type' => 'testimonial',
		'orderby' => 'rand',
		'posts_per_page' => 1,
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			ob_start();
			include plugin_dir_path(__FILE__).'../templates/shortcode.php';
		}
	}
	wp_reset_postdata();
	return ob_get_clean();
}

function fcwp_reviewer_title() {
	echo get_post_meta( get_the_ID(), '_reviewertitle', true );
}

function fcwp_reviewer_company() {
	echo get_post_meta( get_the_ID(), '_company', true );
}