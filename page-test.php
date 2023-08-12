<?php get_header();?>

<?php wp_tag_cloud(array(
							'smallest' => 10,
							'largest' => 16,
							'hide_empty' => false
						)); ?>



<?php get_footer(); ?>