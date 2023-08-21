<?php get_header();

// 성과물 메뉴 클릭 시 초기 화면: document 포스트 타입에 대한 archive ?>
<div class="front-page-container my-3 pb-3 px-3">

    <?php
    $taxonomy = 'project_state';
	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false, // 빈 Term도 출력
		'orderby' => 'slug',
		'order' => 'ASC'
	));

    foreach ($terms as $term) {
        $term_link = get_term_link($term);
        $term_posts = new WP_Query(array(
			'post_type' => 'document',
			'tax_query' => array(
                array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $term->slug,
                ),
            ),
			'posts_per_page' => -1,
		));
    }
    ?>
<?php get_footer(); ?>
