<?php
// 택소노미 value 값: 예) ps01
$taxonomy = 'doc_project_state';
$term_slug = isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : '';
$term = get_term_by('slug', $term_slug, $taxonomy);
$term_name = preg_replace('/\d{2}_/', '', $term->name);

// WP_Query arguments for fetching posts based on 'tax' value
$args = array(
    'post_type' => 'document', // Replace 'aaa' with your actual post type
    'tax_query' => array(
        array(
            'taxonomy' => $taxonomy,
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
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $term_slug,
            ),
    ),
    'orderby' => 'date',
    'order' => 'ASC',
    'posts_per_page' => -1,
);

$query = new WP_Query($args);

$post_count = 0; // 포스트 카운터 변수 초기화

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $post_count++;

        $slug = get_post_field('post_name', get_the_ID()); // 슬러그 ?>

        <div class="post-title fs-4">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <div class="float-right d-inline-block">
                <i class="post-title__doc fas fa-user"></i>
                <span class="post-title__doc"><?php custom_get_the_author();?></span>
                <i class="post-title__doc fas fa-clock"></i>
                <span class="post-title__doc"><?php custom_get_the_time(); ?></span>
                <span class="post-title__doc"> | </span>
                <span class="post-title__doc"><?php echo '#' .$slug; ?></span>
            </div>
        </div>

        <div class="post-content mt-2 px-1">
            <!-- 요약글 -->
            <?php if (has_excerpt()) : ?>
                <div class="excerpt border p-3 mb-3 bg-warning bg-opacity-10">
                    <?php echo get_the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <!-- 컨텐트 내용 -->
            <div class="content mt-2g">
                <?php the_content(); ?>
            </div>

        </div>
        <?php if ($post_count < $query->post_count) { ?><hr><?php } ?>
    <?php }
    wp_reset_postdata();
} else {
    echo '';
}
?>