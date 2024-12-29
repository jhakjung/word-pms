<?php

// 포스트 메타: 카테고리 출력 함수
function custom_get_postmet_parent_category($class = 'badge bg-green') {
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
        $category_names[] = '<a href="' . get_category_link( $parent_category->term_id ) . '"><span class="' . $class . '">' . $parent_category->name . '</span></a>';
    }

    // 자식 카테고리 출력 (부모 카테고리가 출력된 후 추가)
    foreach ( $categories as $category ) {
        if ( $category->category_parent != 0 ) {
            $category_names[] = '<a href="' . get_category_link( $category->term_id ) . '"><span class="' . $class . '">' . $category->name . '</span></a>';
        }
    }

    // 카테고리 리스트 출력
    echo implode(' ', $category_names);  // 태그 간격과 동일하게 공백으로 구분
}

// 포스트 메타: 카테고리 출력 함수
function custom_get_postmeta_category($class = 'badge bg-green') {
    // 포스트에 할당된 카테고리 가져오기
    $categories = get_the_category();
    $parent_categories = [];
    $child_categories = [];

    // 카테고리 배열을 순회하면서 부모와 자식 카테고리 구분
    foreach ($categories as $category) {
        if ($category->parent == 0) {
            // 부모 카테고리일 경우
            $parent_categories[] = $category;
        } else {
            // 자식 카테고리일 경우
            $child_categories[] = $category;
        }
    }

    // 부모 카테고리와 자식 카테고리를 모두 한 줄에 출력
    $output = ''; // 출력할 카테고리들 저장 변수

    // 부모 카테고리가 있다면 먼저 출력
    if (!empty($parent_categories)) {
        foreach ($parent_categories as $parent) {
            // 부모 카테고리 출력 (링크와 span 태그로 감싸기)
            $output .= '<span class="' . esc_attr($class) . '"><a href="' . get_category_link($parent->term_id) . '">' . $parent->name . '</a></span> ';

            // 부모 카테고리와 연결된 자식 카테고리 출력
            foreach ($child_categories as $child) {
                if ($child->parent == $parent->term_id) {
                    // 자식 카테고리 출력 (링크와 span 태그로 감싸기)
                    $output .= '<span class="' . esc_attr($class) . '"><a href="' . get_category_link($child->term_id) . '">' . $child->name . '</a></span> ';
                }
            }
        }
    } else {
        // 부모 카테고리가 없을 경우 자식 카테고리만 출력
        foreach ($child_categories as $child) {
            // 자식 카테고리 출력 (링크와 span 태그로 감싸기)
            $output .= '<span class="' . esc_attr($class) . '"><a href="' . get_category_link($child->term_id) . '">' . $child->name . '</a></span> ';
        }
    }

    // 출력된 카테고리들 한 줄로 출력
    echo rtrim($output); // 끝에 공백 제거
}


// 포스트 메타: 포스트 슬러그 출력 함수
function custom_get_postmeta_postslug() {
	$slug = get_post_field('post_name', get_the_ID()); // 슬러그

	return $slug;
}

// 포스트 메타: author 출력
function custom_get_the_author() {
    $post_author_id = get_post_field('post_author', get_the_ID());
    $author_posts_url = get_author_posts_url($post_author_id);
    $display_name = get_the_author_meta('display_name', $post_author_id);
    echo '<span class="badge bg-light-dark"><a href="' . esc_url($author_posts_url) . '">' . esc_html($display_name) . '</a></span>';
    // echo $display_name;
}

// 포스트 메타: time 출력
function custom_get_the_time() {
	$current = current_time('U');
	$posted = get_the_time('U');
	$diff = $current - $posted;
	echo the_time('y-m-d');
}

