<?php
// 포스트 메타 category 출력: 카테고리별로 badge 태그
function custom_get_the_tax_meta($taxonomy, $class) {
	$terms = get_the_terms(get_the_ID(), $taxonomy);
	if ($terms) {
		foreach ($terms as $term) {
			if ($term) {
				$term_name = $term->name;
				if ($term_name === "즐겨찾기") continue;
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
	echo the_time('y-m-d');
}