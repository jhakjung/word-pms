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