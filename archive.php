<?php get_header();?>

<div class="main container">
	<div class="row">
		<?php get_template_part('template-parts/front/section', 'aside'); ?>
		<?php get_template_part('template-parts/front/section', 'archiveTitle'); ?>
		<?php get_template_part('template-parts/front/section', 'archiveMain'); ?>
	</div>
</div>

<?php get_footer(); ?>