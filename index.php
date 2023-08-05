<?php get_header();?>

<div class="large-hero"><h2>Test</h2></div>

<?php

  while(have_posts()) {
    the_post(); ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_content(); ?>
    <hr>
<?php }

?>


<?php get_footer(); ?>