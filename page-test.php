<?php get_header();?>

<?php get_template_part('template-parts/sections/section', 'tax_ps_list'); ?>

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