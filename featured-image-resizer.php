<?php
/*
Plugin Name: Replace Featured Images with Featured-Image Size
Plugin URI: https://github.com/vestrainteractive/featured-image-resizer
Description: Replaces all featured images with alternate size versions.  Simply replace the featured-image on line 25 with your image size, then activate.
Version: 1.0
Author: Vestra Interactive
Author URI: https://vestrainteractive.com
*/

function replace_featured_images() {
    $args = array(
        'post_type' => 'post', // Adjust post type if needed
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            if (has_post_thumbnail()) {
                $attachment_id = get_post_thumbnail_id();
                $featured_image_size = 'featured-image'; // Replace with your desired size

                // Get the featured-image size URL
                $featured_image_url = wp_get_attachment_image_url($attachment_id, $featured_image_size);

                // Set the featured image URL
                set_post_thumbnail($post->ID, $featured_image_url);
            }
        }
    }
    wp_reset_postdata();
}

register_activation_hook(__FILE__, 'replace_featured_images');

// Include the GitHub Updater class
add_action('plugins_loaded', function() {
    $file = plugin_dir_path( __FILE__ ) . 'class-github-updater.php';

    if ( file_exists( $file ) ) {
        require_once $file;
        error_log( 'GitHub Updater file included successfully.' );
    } else {
        error_log( 'GitHub Updater file not found at: ' . $file );
    }

    // Ensure the class exists before instantiating
    if ( class_exists( 'GitHub_Updater' ) ) {
        // Initialize the updater
        new GitHub_Updater( 'featured-image-resizer', 'https://github.com/vestrainteractive/featured-image-resizer', '1.0.0' ); // Replace with actual values
        error_log( 'GitHub Updater class instantiated.' );
    } else {
        error_log( 'GitHub_Updater class not found.' );
    }
});

?>
