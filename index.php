<?php get_header();?>

<?php get_template_part('template-parts/sections/section', 'stage_cat_list'); ?>

<div class="main container">
	<div class="row">
		<aside class="col-lg-3 border-end">
			<?php get_template_part('template-parts/sections/section', 'system_cat_list2'); ?>

			<br>

			<div class="p-3 border">
				<?php wp_tag_cloud(array('smallest' => 12, 'largest' => 20)); ?>
			</div>


			<div class="p-3 border">
        		<?php dynamic_sidebar('sidebar1'); ?>
        		<?php dynamic_sidebar('sidebar2'); ?>
   			</div>

		</aside>
		<div class="col-lg-9">
			<?php
			$post_count = 0;
			while(have_posts()) {
				the_post();
				$post_count++; ?>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_content(); ?>
				<?php if ($post_count < $wp_query->post_count) { ?> <hr> <?php } ?>
			<?php } ?>

		</div>
	</div>
</div>


<?php get_footer(); ?>