<?php
// 포스트 메타 category 출력: 카테고리별로 badge 태그
function custom_get_the_tax_meta($taxonomy, $class) {
	// 'project_state' Taxonomy에 속하는 Term들을 가져옴
	$terms = get_the_terms(get_the_ID(), $taxonomy);
	if ($terms) {
		foreach ($terms as $term) {
			if ($term) {
				$term_name = $term->name;
				$term_name = preg_replace('/\d{2}_/', '', $term_name); // project_state 조작
				$term_link = get_term_link($term); ?>
				<span class="<?php echo $class; ?>"><a class="my_badge" href="<?php echo $term_link; ?>"><?php echo $term_name; ?></a></span>
			<?php }
		}
	} else {
		echo "-";
	}
}

// 포스트 메타 kewword(태그) 출력
function custom_get_the_tag_meta() {
    $tags = get_the_tags();
    if ($tags) {
        $tag_links = array();
        foreach ($tags as $tag) {
            if ($tag) {
                $tag_name = $tag->name;
                $tag_link = get_tag_link($tag->term_id);
                $tag_links[] = '<span class="badge badge__yellow"><a class="my_badge" href="' . $tag_link . '">' . $tag_name . '</a></span>';
            }
        }
        echo implode(' ', $tag_links);
    } else {
        echo "-";
    }
}

// 포스트 메타 issue_state 출력
function custom_get_the_issue_meta() {
    $terms = get_the_terms(get_the_ID(), 'issue_state');
	if ($terms) {
		$term_name = $terms[0]->name;
		// if ($term_name == "미결") {
		// 	$class = "badge badge__red fs-7 m-1";
		// } elseif ($term_name == "해결") {
		// 	$class = "badge badge__teal fs-7 m-1";
		// } elseif ($term_name == "종결") {
		// 	$class = "badge badge__dark fs-7 m-1";
		// } else {
		// 	$class = "badge badge__purple fs-7 m-1";
		// }
		$term_link = get_term_link($terms[0]);
        echo '<a class="my_badge" href="' . $term_link . '">' . $term_name . '</a>';
	}
}

// 포스트 메타 author 출력
function custom_get_the_author() {
    $post_author_id = get_post_field('post_author', get_the_ID());
    $author_posts_url = get_author_posts_url($post_author_id);
    $display_name = get_the_author_meta('display_name', $post_author_id);
    echo '<a href="' . esc_url($author_posts_url) . '">' . esc_html($display_name) . '</a></span>';
}

// 포스트 메타 time 출력
function custom_get_the_time() {
	$current = current_time('U');
	$posted = get_the_time('U');
	$diff = $current - $posted;

	// if($diff > 0 && $diff < 60*60*24*3) {
	// 	echo sprintf(__('%s 전 게시됨', 'bestmedical'), human_time_diff($posted, $current));
	// } else {
		echo the_time('y-m-d');
}