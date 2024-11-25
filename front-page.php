<?php get_header();?>

<div class="main container">
	<div class="row">
	<?php get_template_part('template-parts/front/section', 'favorite'); ?>
    <?php get_template_part('template-parts/front/section', 'issue'); ?>
    <?php get_template_part('template-parts/front/section', 'document'); ?>
	</div>
</div>

<?php get_footer(); ?>