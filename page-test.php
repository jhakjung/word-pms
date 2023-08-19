<?php get_header(); ?>
<div class="site-main container">

<?php

$tags = get_terms(array(
    'taxonomy' => 'post_tag', // '태그' 택소노미의 이름
    'hide_empty' => false,
));

if (!empty($tags) && !is_wp_error($tags)) {
    foreach ($tags as $tag) {
        echo '<span>' . $tag->name . '</span>';
    }
} else {
    echo 'No tags found.';
}


?>


<?php get_footer(); ?>