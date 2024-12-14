<?php get_header();?>

<div class="main container">
	<div class="row">
		<?php get_template_part('template-parts/front/section', 'favorites'); ?>
		<?php get_template_part('template-parts/front/section', 'tags'); ?>
		<?php get_template_part('template-parts/front/section', 'documents'); ?>
	</div>
</div>

<?php get_footer(); ?>