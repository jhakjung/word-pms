<?php
// Get the 'tax' value from the URL query parameters
$term_value = isset($_GET['project_state']) ? sanitize_text_field($_GET['project_state']) : '';

// WP_Query arguments for fetching posts based on 'tax' value
$args = array(
    'post_type' => 'document', // Replace 'aaa' with your actual post type
    'tax_query' => array(
        array(
            'taxonomy' => 'project_state', // Replace 'tax' with your actual taxonomy
            'field'    => 'slug',
            'terms'    => $term_value,
        ),
    ),
);

// Execute the query
$custom_query = new WP_Query($args);

// Start the loop
if ($custom_query->have_posts()) :
    while ($custom_query->have_posts()) : $custom_query->the_post();
        // Your loop content goes here
        the_title(); // Example: Display the post title
    endwhile;
    wp_reset_postdata(); // Reset the query
else :
    echo '<p>No posts found.</p>';
endif;
?>
