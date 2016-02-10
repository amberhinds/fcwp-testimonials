<?php

add_shortcode( 'fcwp_testimonial', 'fcwp_shortcode' );
function fcwp_shortcode() {
	$args = array(
		'post_type' => 'testimonial',
		'orderby' => 'rand',
	);
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			return get_the_title();
		}
	}
}