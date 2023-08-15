<?php get_header();?>

<?php

	$terms = get_terms(array(
        'taxonomy' => "issue_state",
        'hide_empty' => false,
        'orderby' => 'slug',
        'order' => 'ASC'
    ));

    foreach ($terms as $term) {
        if ($term) {
            $term_name = $term->name;
            $term_link = get_term_link($term);

            // ACF 필드에서 값을 가져와 출력
            $acf_field_value = get_field('issue_state', $term); // 'acf_field_name'은 실제 필드의 이름으로 바꿔야 함

            echo '<span><a href="' . $term_link . '">' . $term_name . ' (' . $acf_field_value . ')' . '</a></span>';
        }
    }

?>



<?php get_footer(); ?>