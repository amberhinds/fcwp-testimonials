<?php

class FCWP_Testimonial_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'		=> 'fcwp_testimonial_widget',
			'description'	=> 'Show testimonials from your customers',
		);
		parent::__construct( 'fcwp_testimonial_widget', 'FCWP Testimonial Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args Widget arguments
	 * @param array $instance The widget options
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}
		$q_args = array(
			'post_type'		=> 'testimonial',
			'orderby'		=> 'rand',
			'posts_per_page' => 1,
		);
		$query = new WP_Query( $q_args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				include plugin_dir_path( __FILE__ ) . '../templates/shortcode.php';
			}
		}
		wp_reset_postdata();
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'fcwp-testimonials' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'FCWP_Testimonial_Widget' );
});