<?php
get_header(); ?>

<div class="document-box my-3 pb-3 px-3">

    <?php
    $taxonomy = 'project_state';
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'orderby' => 'slug',
        'order' => 'ASC',
    ));

    $count = 1; // 넘버링을 위한 변수

    foreach ($terms as $term) {
        $term_link = get_term_link($term, $taxonomy);
        $term_name = preg_replace('/\d{2}_/', '', $term->name); // project_state
        $term_posts = new WP_Query(array(
            'post_type' => 'document',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $term->slug,
                ),
            ),
            'posts_per_page' => -1, // 모든 포스트를 출력하도록 변경
        ));

        $number = str_pad($count, 2, '0', STR_PAD_LEFT); // 넘버링 포맷팅

        ?>

        <div id="shadow-box" class="card bg-secondary bg-opacity-10 pt-2 pb-4 mt-2">
            <h4 class="fs-4 text-center py-2">
                <!-- 넘버링 -->
                <span class="text-danger text-opacity-50 fw-bolder fs-3">
                    <?php echo $number; ?>&nbsp;</span>
                <!-- 카테고리명 -->
                <a href="<?php echo $term_link; ?>" class="card-title">
                    <?php echo $term_name; ?>
                </a>
            </h4>

            <div class="container">
                <ul class="list-group px-3">
                    <?php
                    if ($term_posts->have_posts()) :
                        while ($term_posts->have_posts()) : $term_posts->the_post(); ?>
                            <li class="list-group-item">
                                <a href="<?php the_permalink(); ?>" style="color: black;"><?php echo get_the_title(); ?></a>
                                <?php progress_state(get_the_ID()); ?>
                            </li>
                        <?php endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p>등록된 성과물이 없습니다.</p>';
                    endif; ?>
                </ul>
            </div>
        </div>
        <?php $count++;
    }
    ?>
</div>

<?php get_footer(); ?>
