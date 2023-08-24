<?php
// 택소노미 value 값: 예) ps01
$taxonomy = 'project_state';
$term_slug = isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : '';
$term = get_term_by('slug', $term_slug, $taxonomy);
$term_name = preg_replace('/\d{2}_/', '', $term->name);

// WP_Query arguments for fetching posts based on 'tax' value
$args = array(
    'post_type' => 'document', // Replace 'aaa' with your actual post type
    'tax_query' => array(
        array(
            'taxonomy' => 'project_state',
            'field'    => 'slug',
            'terms'    => $term_slug,
        ),
    ),
); ?>


<?php
// 현재 카테고리에 해당하는 포스트 목록 가져오기
$args = array(
    'post_type' => 'document',
    'tax_query' => array(
            array(
                    'taxonomy' => 'project_state',
                    'field' => 'slug',
                    'terms' => $term_slug,
            ),
    ),
    'posts_per_page' => -1,
);

$query = new WP_Query($args);

$post_count = 0; // 포스트 카운터 변수 초기화

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $post_count++; ?>

        <div class="post-title fs-4">
            <!-- <i class="text-secondary text-opacity-75 fa fa-file fa-sm"></i>&nbsp; -->
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <div class="float-right d-inline-block">
                <span class="post-title__doc"><?php custom_get_the_author();?></span>
                <span class="post-title__doc"> | </span>
                <span class="post-title__doc"><?php custom_get_the_time(); ?></span>
            </div>



        </div>
        <p>
            <?php the_content(); ?>
        </p>
        <?php if ($post_count < $query->post_count) { ?><hr><?php } ?>
    <?php }
    wp_reset_postdata();
} else {
    echo '';
}
?>