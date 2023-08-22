<!-- 카테고리 버튼 그룹 생성 -->
<!-- !!! single-document에서만 호출 가능 !!!, archive에서 호출하면 에러남! -->
<div class="container-fluid mt-3">
    <div class="d-flex flex-wrap justify-content-center">
        <?php

function custom_post_type_archive_link($link, $post_type) {
    // 특정 포스트 타입과 택소노미를 매핑합니다
    $taxonomy_mapping = array(
        $post_type => 'project_states',
        // 다른 포스트 타입과 택소노미도 추가로 매핑할 수 있습니다
    );

    if (isset($taxonomy_mapping[$post_type])) {
        $taxonomy = $taxonomy_mapping[$post_type];
        $terms = get_the_terms(get_the_ID(), $taxonomy);

        if ($terms) {
            $term = reset($terms);
            $link = get_term_link($term, $taxonomy);
        }
    }

    return $link;
}

add_filter('post_type_archive_link', 'custom_post_type_archive_link', 10, 2);


$taxonomy = 'project_state';
$terms = get_terms(array(
    'taxonomy' => $taxonomy,
    'hide_empty' => false,
    'orderby' => 'slug',
    'order' => 'ASC',
));

$current_term = get_the_terms(get_the_ID(), $taxonomy);

if ($current_term && !is_wp_error($current_term)) {
    $current_term_name = $current_term[0]->name;

    foreach ($terms as $term) {
        $term_name = $term->name;
        $term_link = get_term_link($term);
        $term_link = custom_post_type_archive_link($term_link, 'document');

        $button_class = ($term_name == $current_term_name) ? 'btn btn-primary' : 'btn btn-light';
        $term_name = preg_replace('/\d{2}_/', '', $term->name);

        echo '<a href="' . $term_link . '" class="btn m-2 border ' . $button_class . '">' . $term_name . '</a>';
    }
}


        ?>
    </div>
</div>
