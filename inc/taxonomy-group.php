<?php
// 택소노미별 리스트를 출력
function custom_get_tax_list($taxonomy, $class) {
    $hide = true;
    // if ($taxonomy == 'project_state') { $hide = false; }
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => $hide, // 빈 Term도 출력
        'orderby' => 'slug',
        'order' => 'ASC'
    ));
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $term_name = $term->name;
            $term_name = preg_replace('/^\d{2}_/', '', $term_name); // project_state 조작
            $term_link = get_term_link($term);

            // Get the count of 'document' type assigned to the term
            $args = array(
                'post_type' => 'post', // Replace with your custom post type slug
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $term->slug,
                    ),
                ),
            );
            $term_query = new WP_Query($args);
            $term_count = $term_query->found_posts;

            echo '<span class="' . $class . '"><a class="my_badge" href="' . $term_link . '">' . $term_name . ' (' . $term_count . ')</a></span>';
        }
    }
}


// 이슈상태 리스트를 출력
function custom_get_issue_state_list() { ?>
	<?php
	$terms = get_terms(array(
		'taxonomy' => 'issue_state',
		'hide_empty' => false, // 빈 Term도 출력
		'orderby' => 'slug',
		'order' => 'ASC'
	));
	foreach ($terms as $term) {
		if ($term) {
			$term_name = $term->name;
			if ($term_name == "미결") {
				$class = "badge badge__darkOrange fs-7 m-1";
			} elseif ($term_name == "해결") {
				$class = "badge badge__green fs-7 m-1";
			} elseif ($term_name == "종결") {
				$class = "badge badge__dark fs-7 m-1";
			} else {
				$class = "badge badge__purple fs-7 m-1";
			}
			$term_link = get_term_link($term); ?>
			<span class="<?php echo $class; ?>"><a class="my_badge" href="<?php echo $term_link; ?>"><?php echo $term_name . '(' . $term->count . ')'; ?></a></span>
		<?php }
	} ?>
<?php }

// 카테고리 archive 페이지로 연결해주는 링크 생성 함수
function custom_cat_archive_link($category_slug) {
    $category = get_category_by_slug($category_slug);
    if ($category) {
        $category_archive_url = get_term_link($category);
        if (!is_wp_error($category_archive_url)) {
            return $category_archive_url;
        }
    }
    return '';
}

// Archive title을 출력할 때 괄호를 없애주는 함수
function custom_get_the_archive_title() {
	$archive_title = get_the_archive_title();
	// 현재 페이지가 카테고리 아카이브 페이지인지 확인
	$archive_title = str_replace(array('[', ']'), '', $archive_title);
	if (is_tag()) {
		$archive_title = str_replace('태그', '키워드', $archive_title);
	}
	return $archive_title;
}