# Custom Post Types

Posts vs Pages Background
-------------------------

- Posts have time based listings and can be organized with taxonomies
- Pages can be hierarchical and typically don't use taxonomies
- Both live in the wp_posts table of your database
- CPTs you create and operate more like posts than pages
- Codex reference: https://codex.wordpress.org/Post_Types

Creating a CPT
--------------

1. Register the post type using register_post_type() function
2. Hook into 'init' which runs after WP has finished loading but before any headers are sent.  Most of WP is loaded at this stage.  https://codex.wordpress.org/Plugin_API/Action_Reference/init
3. Set up labels and arguments for post type - see https://codex.wordpress.org/Function_Reference/register_post_type for all options
4. Make sure it's there
5. Reset Permalinks


# Register A Taxonomy

Taxonomies Explained
--------------------

- Taxonomies are ways of grouping things together
- Hierarchical (Categories) or not (Tags)
- Codex reference: https://codex.wordpress.org/Taxonomies

Creating A Taxonomy
-------------------

1. Register a taxonomy using register_taxonomy() function
2. Also hook into 'init'
3. See https://codex.wordpress.org/Function_Reference/register_taxonomy for label and argument options
4. Reset permalinks


# Add CPT to Dashboard

We want our custom post type to show up in the Dashboard "At A Glance" Meta Box.  Put this code in your toolbox so you can copy & paste it in the future... now we will walk through it:

Add to At A Glance
------------------

1. Yay! dashboard_glance_items is a hook.  Create your function.
2. Set up your $post_type variable using get_post_types() - https://codex.wordpress.org/Function_Reference/get_post_types
3. Create a foreach loop and output the post type name and post count - https://codex.wordpress.org/Function_Reference/wp_count_posts
4. If the user can edit the post, link the name to the edit screen

Fix the icon
------------

1. Override the default WP CPT icon with style html added to the admin head
2. Hook into admin_head so it only shows where we want it
3. Target with CSS class set in previous function
4. The echo statement can be repeated for every CPT

# Customize Individual Post Editing Experience

More prime snippets to include in your library.

Change Title Text Placeholder
----------------------------

1. Create a function hooked into 'gettext,' a filter hook  applied to translated text by the internationalization functions. Works even if internationalization is not being used.  https://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
2. This is set up using a switch statement (which functions like IF statements), so that you can add in multiple CPTs

Add Meta Box & Meta Data Fields
-------------------------------

1. Create function using add_meta_box() - https://developer.wordpress.org/reference/functions/add_meta_box/
2. Register your metabox in the args for the applicable register_post_type() function
3. When adding fields, first set up nonces (WP security tokens) to protect from hacking, etc.  - Learn about nonces at https://codex.wordpress.org/WordPress_Nonces
4. We're using wp_create_nonce() - https://codex.wordpress.org/Function_Reference/wp_create_nonce
5. Create variables that will display and meta data if it already exists.
6. Create the HTML for the fields using the variables to display what has been entered in each field, if applicable.

Make Meta Data Save With Post Update
------------------------------------

1. Hook into save_post
2. Verify that we submitted data through a form
3. Verify this came from the current screen and with proper authorization because save_post can be triggered at other times
4. Verify the user can edit the post
5. If all systems are go, find and save the data using an array
6. Add values of $testimonial_meta as custom fields, but don't add them more than once
7. If the field already has a value, use update_post_meta() - https://codex.wordpress.org/Function_Reference/update_post_meta
8. If the field does not already have a value, use add_post_meta() - https://codex.wordpress.org/Function_Reference/add_post_meta
9. If the field was previously filled but is now blank, use delete_post_meta() - https://codex.wordpress.org/Function_Reference/delete_post_meta