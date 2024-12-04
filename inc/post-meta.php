<?php

// 포스트 메타: 카테고리 출력 함수
function custom_get_postmet_parent_category($class = 'badge badge__blue text-white') {
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
// 포스트 메타: 카테고리 출력 함수
function custom_get_postmeta_category($class = 'badge badge__blue text-white') {
    $categories = get_the_category();
    $category_names = array();

    foreach ( $categories as $category ) {
        if ( $category->category_parent != 0 ) { // 자식 카테고리만 처리
            $parent = get_category($category->category_parent);

            // 부모 카테고리가 "documents"인 경우 "성과물: " 추가
            if ( $parent && $parent->slug === 'documents' ) {
                $category_names[] = '<a href="' . get_category_link( $category->term_id ) . '"><span class="' . $class . '">성과물: ' . $category->name . '</span></a>';
            } else {
                $category_names[] = '<a href="' . get_category_link( $category->term_id ) . '"><span class="' . $class . '">' . $category->name . '</span></a>';
            }
        }
    }

    // 카테고리 리스트 출력
    echo implode(' ', $category_names);  // 태그 간격과 동일하게 공백으로 구분
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

