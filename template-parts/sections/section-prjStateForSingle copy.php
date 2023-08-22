<!-- 카테고리 버튼 그룹 생성 -->
<!-- !!! single-document에서만 호출 가능 !!!, archive에서 호출하면 에러남! -->
<div class="container-fluid mt-3">
    <div class="d-flex flex-wrap justify-content-center">
        <?php

function custom_term_link($taxonomy, $term, $post_type) {
    $term_obj = get_term_by('slug', $term, $taxonomy);

    if ($term_obj) {
        return get_post_type_archive_link($post_type) . '?' . $taxonomy . '=' . $term_obj->slug;
    }

    return false;
}
        $taxonomy = 'project_state';
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'orderby' => 'slug',
            'order' => 'ASC',
        ));

        $current_term = get_the_terms(get_the_ID(), $taxonomy);
        $current_term_name = $current_term[0]->name;

        foreach ($terms as $term) {
            $term_name = $term->name;
            // 직접 URL을 구성하여 포스트 타입과 분류를 연결합니다.
            $term_link = custom_term_link($taxonomy, $term, 'document');

            $button_class = '';
            if ($term_name == $current_term_name) {
                $button_class = 'btn btn-primary';
                $current_term_slug = $term->slug;
            } else {
                $button_class = 'btn-light';
            }
            $term_name = preg_replace('/\d{2}_/', '', $term->name);

            echo '<a href="' . $term_link . '" class="btn m-2 border ' . $button_class . '">' . $term_name . '</a>';
        }

        ?>
    </div>
</div>
