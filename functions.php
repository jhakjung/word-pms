<?php
// Theme resource FIles
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
function enqueue_custom_scripts() {
	wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
	wp_enqueue_style('my-style', get_stylesheet_uri());
	wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
	wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
}

// 성과물 포스트 타입 등록
function document_post_types() {
	register_post_type('document', array(
		'show_in_rest' => true,
		// 'capability_type' => 'document',
		// 'map_meta_cap'  => true,
		'supports' => array('title', 'editor', 'comments', 'author', 'tag'),
		'rewrite' => array('slug' => 'documents'),
		'taxonomies'  => array('category'),
		'has_archive' => true,
		'public' => true,
		'labels' => array(
			'name' => '성과물',
			'add_new_item' => '성과물 추가',
			'edit_item' => '성과물 수정',
			'all_items' => '성과물 목록',
			'singular_name' => '성과물' ),
		'menu_icon' => 'dashicons-media-document'
	));
}
add_action('init', 'document_post_types');

// 카테고리명을 출력하는 함수
function get_all_category_list() {
	$args = array(
		'hide_empty' => 0, // 미분류 카테고리를 포함하여 모든 카테고리를 가져오도록 설정
	);

	$categories = get_categories($args);

	return $categories;
}

// "추진단계" 카테고리의 자식 카테고리를 출력하는 함수
function get_document_category_children() {
	$parent_category = get_category_by_slug('stage'); // "추진단계" 카테고리를 slug로 가져옴

  	if ($parent_category) {
    	$args = array(
        	'hide_empty' => 0, // 미분류 카테고리를 포함하여 모든 자식 카테고리를 가져오도록 설정
          	'parent' => $parent_category->term_id, // 부모 카테고리의 ID를 지정하여 자식 카테고리만 가져옴
			'orderby' => 'slug',
			'order' => 'ASC'
      	);

      	$children_categories = get_categories($args);
	}

	return $children_categories;
}