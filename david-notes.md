# Everyone know what a shortcode is?

1. Create shortcode file
2. Making shortcode return __whatever__
3. Making shortcode return name of random testimonial using `WP_Query`, loop, `get_the_title`
4. Replace `get_the_title` with `the_title` and output buffering
5. Move `the_tile` to a separate template file.
6. Slime out the template file
7. Crude replace slimed fields in template
8. Make template tags for use of `get_post_meta` in template

# Let's add some styling

1. Class around the template & style it in file
2. Move style(s) into own enqueued stylesheet

# Let's create a widget

1. Add the file and include it
2. Copypasta from Codex the `WP_Widget` class
3. Customize output of the widget — slime something there
4. Add in the loop again, or maybe use get_posts to output something from a real testimonial
5. Add title to widget
6. Add dropdown of testimonials to widget
