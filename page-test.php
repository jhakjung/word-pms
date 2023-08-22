<?php get_header(); ?>
<div class="site-main container">

<?php

$taxonomy = 'project_state';
$terms = get_terms(array(
    'taxonomy' => $taxonomy,
    'hide_empty' => false,
    'orderby' => 'slug',
    'order' => 'ASC',
));

$current_term = get_the_terms(get_the_ID(), $taxonomy);

if ($current_term && !is_wp_error($current_term)) {
    $current_term_name = $current_term[0]->name;

    foreach ($terms as $term) {
        $term_name = $term->name;
        $term_link = get_term_link($term);
        $term_link = custom_post_type_archive_link($term_link, 'document');

        $button_class = ($term_name == $current_term_name) ? 'btn btn-primary' : 'btn btn-light';
        $term_name = preg_replace('/\d{2}_/', '', $term->name);

        echo '<a href="' . $term_link . '" class="btn m-2 border ' . $button_class . '">' . $term_name . '</a>';
    }
}


?>


<?php get_footer(); ?>