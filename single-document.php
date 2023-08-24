<?php get_header();

get_template_part('template-parts/sections/section', 'prjStateForSingle'); ?>

<hr>

<div class="main container">
	<div class="row pt-1 pb-4">

		<div class="col-md-3">
			<?php
			$taxonomy = 'project_state';
			$term = get_the_terms(get_the_ID(), $taxonomy);
			$term_name = $term[0]->name;
			$term_slug = $term[0]->slug;
			?>

			<ul class="list-group doc-list">
                <li class="list-group-item border text-dark bg-light px-4">
                    <?php echo $term_name; ?>
                </li>
                <?php
                $args = array(
                    'post_type' => 'document',
                    'tax_query' => array(
                            array(
                                    'taxonomy' => $taxonomy,
                                    'field' => 'slug',
                                    'terms' => $term_slug,
                            ),
                    ),
                    'posts_per_page' => -1
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                            $query->the_post();	?>

                            <li class="list-group-item px-4">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <?php progress_state(get_the_ID()); ?>
                            </li>
                    <?php }
                    wp_reset_postdata();
                } else {
                    echo '<li class="list-group-item">해당 프로젝트 단계에 등록된 성과물이 없습니다.</li>';
                }
                ?>
            </ul>
        </div> <!-- col-3 -->

        <div class="col-md-9">
            <?php get_template_part('template-parts/sections/section', 'docMainSingle'); ?>
        </div> <!-- col-9 -->

	</div> <!-- row -->
</div> <!-- main -->

<?php get_footer(); ?>