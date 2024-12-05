<!-- 카테고리 버튼 그룹 생성 -->
<!-- !!! single-document에서만 호출 가능 !!!, archive에서 호출하면 에러남! -->
<div class="container-fluid mt-3">
    <div class="d-flex flex-wrap justify-content-center">
        <?php
        $taxonomy = 'doc_project_state';
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'orderby' => 'slug',
            'order' => 'ASC',
        ));

        if ('document' == get_post_type()) {
            $current_term = get_the_terms(get_the_ID(), $taxonomy);
            $current_term_name = $current_term[0]->name;
        } elseif (is_archive()){
            $tr_term_slug = isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : '';
            $tr_term = get_term_by('slug', $tr_term_slug, $taxonomy);
            $current_term_name = $tr_term->name;
        } else {
            $current_term_name = '';
        }

        foreach ($terms as $term) {
            $term_name = $term->name;
            $term_link = generate_tax_archive_link($taxonomy, $term->slug);
            $button_class = ($term_name == $current_term_name) ? 'btn btn-primary' : 'btn btn-light';
            $term_name = preg_replace('/\d{2}_/', '', $term_name);
            echo '<a href="' . $term_link . '" class="btn m-2 border ' . $button_class . '">' . $term_name . '</a>';
        } ?>

    </div>
</div>
