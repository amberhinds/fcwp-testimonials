<?php
//* This file adds the custom post type to our testimonials plugin with a taxonomy and custom meta box options.

/**
 * Create testimonials custom post type.
 *
 * Registers the testimonial custom post type.
 *
 * @see register_post_type
 */
add_action( 'init', 'fcwp_testimonials_post_type' );
function fcwp_testimonials_post_type() {

    $labels = array(
		'name'               => _x( 'Testimonials', 'post type general name', 'fcwp-testimonials' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name', 'fcwp-testimonials' ),
		'menu_name'          => _x( 'Testimonials', 'admin menu', 'fcwp-testimonials' ),
		'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'fcwp-testimonials' ),
		'add_new'            => _x( 'Add New', 'testimonial', 'fcwp-testimonials' ),
		'add_new_item'       => __( 'Add New Testimonial', 'fcwp-testimonials' ),
		'new_item'           => __( 'New Testimonial', 'fcwp-testimonials' ),
		'edit_item'          => __( 'Edit Testimonial', 'fcwp-testimonials' ),
		'view_item'          => __( 'View Testimonial', 'fcwp-testimonials' ),
		'all_items'          => __( 'All Testimonials', 'fcwp-testimonials' ),
		'search_items'       => __( 'Search Testimonials', 'fcwp-testimonials' ),
		'parent_item_colon'  => __( 'Parent Testimonials:', 'fcwp-testimonials' ),
		'not_found'          => __( 'No testimonials found.', 'fcwp-testimonials' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash.', 'fcwp-testimonials' )
	);

	$args = array(
		'labels' 		=> $labels,
        'has_archive' 	=> true,
        'public' 		=> true,
        'rewrite' 		=> array( 'slug' => 'testimonial' ),
        'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-testimonial',
        'register_meta_box_cb' => 'add_testimonial_metaboxes'
	);

    register_post_type( 'testimonial', $args );

}

/**
 * Create taxonomy for testimonials CPT.
 *
 * Registers a hierarchical (like categories) taxonomy for the testimonials custom post type
 *
 * @see register_taxonomy
 */
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

	register_taxonomy( 'testimonials-tax', 'testimonial', $args );

}


/**
 * Add testimonials to dashboard "At A Glance" metabox.
 *
 * Use the get_post_types() function to return all CPTs,
 * then loop through them and generate a count of published posts
 * and add it to "At A Glance" with an edit link if the current user has permission to edit them.
 * 
 * @see get_post_types
 * @see wp_count_posts
 * @see current_user_can
 */
add_action( 'dashboard_glance_items', 'fcwp_cpt_at_glance' );
function fcwp_cpt_at_glance() {
    $args = array(
        'public' 	=> true,
        '_builtin'	=> false
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

/**
 * Set custom icon for testimonials on dashboard.
 *
 * The default WP icon for CPTs is the same pushpin used for posts
 * We want our at a glance icon to match the dashicon set when registering the post type.
 * This echos style html in the header of the admin panel
 */
add_action( 'admin_head', 'fcwp_dashboard_cpts_css' );
function fcwp_dashboard_cpts_css() {
       echo '<style type="text/css">#dashboard_right_now .testimonial-count a:before, #dashboard_right_now .testimonial-count span:before { content: "\f473" !important; } </style>';
}

/**
 * Change CPT title text placeholder.
 *
 * Changes the "Enter Title Here" placehoder text that defaults with post titles
 * so users will know that this is where the name of the reviewer goes when creating a new testimonial. 
 *
 * @global object $post Current post information.
 * Learn about global variables at https://codex.wordpress.org/Global_Variables
 *
 * @param string $translation String to be translated.
 * @return string Modified translation string.
 */
add_action( 'gettext', 'fcwp_change_title_text' );
function fcwp_change_title_text( $translation ) {
    global $post;
    if ( isset( $post ) ) {
        switch( $post->post_type ){
            case 'testimonial' :
                if( $translation == 'Enter title here' ) return 'Enter Reviewer Name Here';
            break;
        }
    }
    return $translation;
}

/**
 * Add testimonials details metabox.
 *
 * Create a metabox only on the testimonials CPT to hold our custom meta data needed for the CPT
 * Don't forget to add it to the args for your register_post_type() function above
 *
 * @see add_meta_box
 */
function add_testimonial_metaboxes() {
    add_meta_box( 'fcwp_testimonial_details', 'Testimonial Details', 'fcwp_testimonial_details', 'testimonial', 'normal', 'default' );
}

/**
 * Add fields to testimonials details metabox.
 *
 * Description.
 *
 * @global object $post
 *
 * @see wp_create_nonce
 * @see get_post_meta
 */
function fcwp_testimonial_details() {
    global $post;

    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="testimonialmeta_noncename" id="testimonialmeta_noncename" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

    // Get the testimonial details if they have already been entered
    $testimoniallink = get_post_meta( $post->ID, 'fcwp_testimoniallink', true );
    $reviewertitle = get_post_meta( $post->ID, 'fcwp_reviewertitle', true );
    $company = get_post_meta( $post->ID, 'fcwp_company', true );

    // Display the fields
    echo "<p>Enter Reviewer's Title</p>";
    echo '<input type="text" name="fcwp_reviewertitle" value="' . $reviewertitle  . '" class="widefat" />';
    echo "<p>Enter Reviewer's Company</p>";
    echo '<input type="text" name="fcwp_company" value="' . $company  . '" class="widefat" />';
    echo '<p>Enter Testimonial Link</p>';
    echo '<input type="text" name="fcwp_testimoniallink" value="' . $testimoniallink  . '" class="widefat" />';

}

/**
 * Save the metabox data when testimonial is saved.
 *
 * Description.
 *
 * @see get_post_meta, update_post_meta, delete_post_meta
 *
 * @param int $post_id Post ID.
 * @param object $post Current post information.
 * @return int Post ID.
 */
add_action( 'save_post', 'fcwp_save_testimonial_meta', 1, 2 );
function fcwp_save_testimonial_meta( $post_id, $post ) {

    // Verify that we actually submitted data through a form
    if ( !isset( $_POST['testimonialmeta_noncename'] ) || empty( $_POST['testimonialmeta_noncename'] ) ){
	    return $post->ID;
    }

    // verify this came from the our screen and with proper authorization because save_post can be triggered at other times
    if ( !wp_verify_nonce( $_POST['testimonialmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
    	return $post->ID;
    }

    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ) ){
        return $post->ID;
    }

    // After authentication, find and save the data using an array
    $testimonial_meta['fcwp_testimoniallink']	= $_POST['fcwp_testimoniallink'];
    $testimonial_meta['fcwp_reviewertitle']		= $_POST['fcwp_reviewertitle'];
    $testimonial_meta['fcwp_company']			= $_POST['fcwp_company'];

    // Add values of $testimonial_meta as custom fields
    foreach ( $testimonial_meta as $key => $value ) { // Cycle through the $testimonial_meta array
        if ( $post->post_type == 'revision' ){
	        return; // Don't store custom data twice
	    }

        $value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
        if ( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
            update_post_meta( $post->ID, $key, $value );
        } else { // If the custom field doesn't have a value
            add_post_meta( $post->ID, $key, $value );
        }

        if ( ! $value ) {
	        delete_post_meta( $post->ID, $key ); // Delete if blank
	    }
    }

}