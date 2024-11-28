<?php get_header();?>

<div class="main container">
	<div class="row">
		<?php get_template_part('template-parts/ections/section', 'aside'); ?>
		<?php get_template_part('template-parts/sections/section', 'pageHeader'); ?>
		<?php get_template_part('template-parts/sections/section', 'siteMain'); ?>
	</div>
</div>

<?php get_footer(); ?>