<?php get_header();?>

<div class="container-fluid border-bottom">
	<div class="d-flex flex-wrap justify-content-center">

    <?php
// 'project_state' Taxonomy에 속하는 Term들을 가져옴
$terms = get_terms(array(
    'taxonomy' => 'project_state',
    'hide_empty' => false, // 빈 Term도 출력
));

if (!empty($terms) && !is_wp_error($terms)) {
    echo '<ul>';
    foreach ($terms as $term) {
        echo '<li>' . $term->name . '</li>';
    }
    echo '</ul>';
} else {
    echo 'No terms found.';
}
?>

	</div>
</div>

<?php
// 'project_state' Taxonomy 중 "ps01" Slug를 가진 Term을 가져옴
$term = get_term_by('slug', 'ps01', 'project_state');

if ($term) {
    // 'project_state' Taxonomy의 'ps01' Slug를 가진 Post들을 가져옴
    $args = array(
        'post_type' => 'post', // 해당 Taxonomy에 속한 Post들 중에서 검색
        'tax_query' => array(
            array(
                'taxonomy' => 'project_state',
                'field' => 'term_id',
                'terms' => $term->term_id,
            ),
        ),
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<ul>';
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li>' . get_the_title() . '</li>';
        }
        echo '</ul>';

        wp_reset_postdata(); // 반복문 후 원래의 Post 데이터를 복원하기 위해 리셋
    } else {
        echo 'No posts found.';
    }
} else {
    echo 'Term not found.';
}
?>




<?php get_footer(); ?>