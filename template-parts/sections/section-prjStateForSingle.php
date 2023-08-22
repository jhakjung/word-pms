<!-- 카테고리 버튼 그룹 생성 -->
<!-- !!! single-document에서만 호출 가능 !!!, archive에서 호출하면 에러남! -->
<div class="container-fluid mt-3">
    <div class="d-flex flex-wrap justify-content-center">
        <?php

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
        $term_value = 'ps01';
        $term_link = generate_tax_archive_link($term_value);

        $button_class = ($term_name == $current_term_name) ? 'btn btn-primary' : 'btn btn-light';
        $term_name = preg_replace('/\d{2}_/', '', $term->name);

        echo '<a href="' . $term_link . '" class="btn m-2 border ' . $button_class . '">' . $term_name . '</a>';
    }
}


        ?>
    </div>
</div>
