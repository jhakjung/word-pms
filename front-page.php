<?php get_header();

// WP_Query를 사용하여 포스트를 slug 오름차순으로 정렬
$args = array(
    'post_type'      => 'post',        // 포스트 타입 지정
    'posts_per_page' => -1,            // 모든 포스트 불러오기
    'orderby'        => 'slug',        // slug 기준으로 정렬
    'order'          => 'ASC',         // 오름차순 정렬
);

$query = new WP_Query($args);

// 포스트가 있을 경우
if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        // 각 포스트의 제목 및 링크 출력
        echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
        echo '<p>Slug: ' . get_post_field('post_name', get_the_ID()) . '</p>';
    endwhile;
else :
    echo '<p>포스트가 없습니다.</p>';
endif;

// 리셋
wp_reset_postdata();


get_footer(); ?>
