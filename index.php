<?php get_header();?>

<?php get_template_part('template-parts/sections/section', 'doc_cat_list'); ?>

<?php

  while(have_posts()) {
    the_post(); ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_content(); ?>
    <hr>
<?php }

?>


<?php get_footer(); ?>