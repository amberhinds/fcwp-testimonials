<style>
	.testimonial-citation {
		font-weight: bold;
		font-style: italic;
	}
</style>

<?php the_content() ?>

<p class="testimonial-citation">&mdash;<?php the_title(); ?>,
	<?php fcwp_reviewer_title(); ?> at <?php fcwp_reviewer_company(); ?></p>