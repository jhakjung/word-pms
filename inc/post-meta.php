<?php
// (미사용)포스트 메타 category 출력: 카테고리별로 badge 태그
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

// 포스트메타 태그 출력
function custom_get_tags($margin) {
    $tags = get_the_tags();
    if ($tags) {
        $tag_links = array();
        foreach ($tags as $tag) {
            if ($tag) {
                $tag_name = $tag->name;
                $tag_link = get_tag_link($tag->term_id);
                $tag_links[] = '<span class="' . $margin . ' badge badge__yellow text-dark"><a href="' . $tag_link . '">' ."#". $tag_name . '</a></span>';
            }
        }
        echo implode(' ', $tag_links);
    } else {
        echo "-";
    }
}

// 전체 태그 List 출력
function custom_get_all_tags($margin) {
    $tags = get_tags([
        'hide_empty' => false, // 게시글에 사용되지 않은 태그도 포함
    ]);
    if ($tags) {
        $tag_links = array();
        foreach ($tags as $tag) {
            if ($tag) {
                $tag_name = $tag->name;
                $tag_link = get_tag_link($tag->term_id);
                $tag_links[] = '<span class="' . $margin . ' badge badge__yellow text-dark"><a href="' . $tag_link . '">' ."#". $tag_name . '</a></span>';
            }
        }
        echo implode(' ', $tag_links);
    } else {
        echo "-";
    }
}

// 성과물 단계 출력
function custom_get_document_category() {
    // "document" 카테고리의 자식 카테고리 가져오기
    $parent_category_id = get_cat_ID('성과물'); // "성과물" 카테고리의 ID
    $args = [
        'parent' => $parent_category_id, // 부모 카테고리 ID로 자식 카테고리 가져오기
        'hide_empty' => false, // 사용되지 않은 카테고리도 포함
        'orderby' => 'slug', // 슬러그명으로 정렬
        'order' => 'ASC', // 오름차순 정렬
    ];
    $child_categories = get_categories($args);

    // 자식 카테고리마다 .card를 출력
    foreach ($child_categories as $category) {
        $category_name = $category->name;
        $category_link = get_category_link($category->term_id);
        $category_links[] = '<span class="badge bg-dark bg-gradient m-1"><a href="' . $category_link . '">' . $category_name . '</a></span>';
    }
    echo implode(' ', $category_links);
}


// (미사용)포스트 메타 issue_state 출력
// function custom_get_the_issue_meta() {
//     $terms = get_the_terms(get_the_ID(), 'issue_state');
// 	if ($terms) {
// 		$term_name = $terms[0]->name;
// 		if ($term_name == "N/A") {
// 			echo "-";
// 		} else {
// 			$term_link = get_term_link($terms[0]);
// 			echo '<a class="my_badge" href="' . $term_link . '">' . $term_name . '</a>';
// 		}
// 	}
// }

// 포스트 메타 author 출력
function custom_get_the_author() {
    $post_author_id = get_post_field('post_author', get_the_ID());
    $author_posts_url = get_author_posts_url($post_author_id);
    $display_name = get_the_author_meta('display_name', $post_author_id);
    echo '<span class="badge bg-light-dark"><a href="' . esc_url($author_posts_url) . '">' . esc_html($display_name) . '</a></span>';
    // echo $display_name;
}

// 포스트 메타 time 출력
function custom_get_the_time() {
	$current = current_time('U');
	$posted = get_the_time('U');
	$diff = $current - $posted;
	echo the_time('y-m-d');
}

// (미사용)댓글 수 표시
function custom_get_comments_number() {
	$comment_count = get_comments_number();
	if ($comment_count > 0) {
		echo '<a href="' . get_comments_link() . '">' . $comment_count . '</a>';
	} elseif (comments_open()) {
		echo '<a href="' . get_comments_link() . '">0</a>';
	}
}

// 포스트 메타: 카테고리 출력 함수
function custom_get_postmeta_category() {
    $categories = get_the_category();
    $category_names = array();
    $parent_categories = array();

    // 부모 카테고리만 한 번 추가
    foreach ( $categories as $category ) {
        if ( $category->category_parent == 0 ) {
            $parent_categories[] = $category;
        }
    }

    // 부모 카테고리 먼저 출력
    foreach ( $parent_categories as $parent_category ) {
        $category_names[] = '<a href="' . get_category_link( $parent_category->term_id ) . '"><span class="badge badge__blue text-white mx-1">' . $parent_category->name . '</span></a>';
    }

    // 자식 카테고리 출력 (부모 카테고리가 출력된 후 추가)
    foreach ( $categories as $category ) {
        if ( $category->category_parent != 0 ) {
            $category_names[] = '<a href="' . get_category_link( $category->term_id ) . '"><span class="badge badge__blue text-white mx-1">' . $category->name . '</span></a>';
        }
    }

    // 카테고리 리스트 출력
    echo implode( '', $category_names );
}


// 포스트 메타: 포스트 슬러그 출력 함수
function custom_get_postmeta_postslug() {
	$slug = get_post_field('post_name', get_the_ID()); // 슬러그

	return $slug;
}