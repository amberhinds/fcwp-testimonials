<?php
//* This file add the custom post type to our testimonials plugin with a taxonomy and custom options.

//* Create testimonials custom post type
add_action( 'init', 'fcwp_testimonials_post_type' );
function fcwp_testimonials_post_type() {

    $labels = array(
		'name'               => _x( 'Testimonials', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Testimonials', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'testimonial', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Testimonial', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Testimonial', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Testimonial', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Testimonial', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Testimonials', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Testimonials', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Testimonials:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No testimonials found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash.', 'your-plugin-textdomain' )
	);

    register_post_type(
    	'testimonial',
        array(
            'labels' 		=> $labels,
            'has_archive' 	=> true,
            'public' 		=> true,
            'rewrite' 		=> array( 'slug' => 'testimonial' ),
            'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail' ),
	        'menu_position' => 5,
	        'menu_icon'     => 'dashicons-testimonial',
            'register_meta_box_cb' => 'add_testimonial_metaboxes'
        )
    );

}

//* Create taxonomy for testimonials CPT
add_action( 'init', 'fcwp_testimonials_tax' );
function fcwp_testimonials_tax() {
	$labels = array(
		'name'              => _x( 'Testimonial Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Testimonial Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Testimonial Categories' ),
		'all_items'         => __( 'All Testimonial Categories' ),
		'parent_item'       => __( 'Parent Testimonial Category' ),
		'parent_item_colon' => __( 'Parent Testimonial Category:' ),
		'edit_item'         => __( 'Edit Testimonial Category' ),
		'update_item'       => __( 'Update Testimonial Category' ),
		'add_new_item'      => __( 'Add New Testimonial Category' ),
		'new_item_name'     => __( 'New Testimonial Category' ),
		'menu_name'         => __( 'Testimonial Categories' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'testimonial-categories' ),
	);
	register_taxonomy( 'testimonials-tax', 'testimonial', $args);
}

//* Change CPT title text
add_action( 'gettext', 'fcwp_change_title_text' );
function fcwp_change_title_text( $translation ) {
    global $post;
    if( isset( $post ) ) {
        switch( $post->post_type ){
            case 'testimonial' :
                if( $translation == 'Enter title here' ) return 'Enter Reviewer Name Here';
            break;
        }
    }
    return $translation;
}


//* Add testimonials to dashboard "At A Glance" metabox
add_action( 'dashboard_glance_items', 'fcwp_cpt_at_glance' );
function fcwp_cpt_at_glance() {
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
        if ( current_user_can( 'edit_posts' ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            } else {
            $output = '<span>' . $num . ' ' . $text . '</span>';
                echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
            }
    }
}

//* Set custom icon for testimonials on dashboard
add_action('admin_head', 'fcwp_dashboard_cpts_css');
function fcwp_dashboard_cpts_css() {
       echo '<style type="text/css">#dashboard_right_now .testimonial-count a:before, #dashboard_right_now .testimonial-count span:before { content: "\f473" !important; } </style>';
}

//* Add testimonials details metabox
function add_testimonial_metaboxes() {
    add_meta_box('fcwp_testimonial_details', 'Testimonial Details', 'fcwp_testimonial_details', 'testimonial', 'normal', 'default');
}

//* Add fields to slide details metabox
function fcwp_testimonial_details() {
    global $post;

    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="testimonialmeta_noncename" id="testimonialmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    // Get the slide details if they have already been entered
    $testimoniallink = get_post_meta($post->ID, '_testimoniallink', true);
    $reviewertitle = get_post_meta($post->ID, '_reviewertitle', true);
    $company = get_post_meta($post->ID, '_company', true);

    // Display the fields
    echo "<p>Enter Reviewer's Title</p>";
    echo '<input type="text" name="_reviewertitle" value="' . $reviewertitle  . '" class="widefat" />';
    echo "<p>Enter Reviewer's Company</p>";
    echo '<input type="text" name="_company" value="' . $company  . '" class="widefat" />';
    echo '<p>Enter testimonial Link</p>';
    echo '<input type="text" name="_testimoniallink" value="' . $testimoniallink  . '" class="widefat" />';

}

//* Save the metabox data when testimonial is saved
add_action('save_post', 'fcwp_save_testimonial_meta', 1, 2);
function fcwp_save_testimonial_meta($post_id, $post) {

    // Verify that we actually submitted data through a form
    if ( !isset( $_POST['testimonialmeta_noncename'] ) || empty( $_POST['testimonialmeta_noncename'] ) ){
	    return $post->ID;
    }

    // verify this came from the our screen and with proper authorization because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['testimonialmeta_noncename'], plugin_basename(__FILE__) )) {
    	return $post->ID;
    }

    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;

    // After authentication, find and save the data using an array
    $testimonial_meta['_testimoniallink'] = $_POST['_testimoniallink'];
    $testimonial_meta['_reviewertitle'] = $_POST['_reviewertitle'];
    $testimonial_meta['_company'] = $_POST['_company'];

    // Add values of $testimonial_meta as custom fields
    foreach ($testimonial_meta as $key => $value) { // Cycle through the $testimonial_meta array
        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }

}