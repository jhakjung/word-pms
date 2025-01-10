<?php get_header();?>

<div class="main container">
	<div class="row">
		<div class="col-lg-5">
			<?php get_template_part('template-parts/front/section', 'favorites'); ?>
		</div>
		<div class="col-lg-7">
			<?php get_template_part('template-parts/front/section', 'tags'); ?>
		</div>
	</div>
	<hr>
	<div class="row">
		<?php get_template_part('template-parts/front/section', 'documents'); ?>
		<?php get_template_part('template-parts/front/section', 'etc'); ?>
	</div>
</div>

<?php get_footer(); ?>