<?php
require get_template_directory() . '/inc/post-meta.php';
require get_template_directory() . '/inc/taxonomy-group.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/inc/comment-template.php';

// Theme resource FIles
function enqueue_custom_scripts() {
	wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
	wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
	wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
	wp_enqueue_style('my-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// 성과물 포스트 타입 등록
function document_post_types() {
	register_post_type('document', array(
		'show_in_rest' => true,
		// 'capability_type' => 'document',
		// 'map_meta_cap'  => true,
		'supports' => array('title', 'editor', 'comments', 'author'),
		'rewrite' => array('slug' => 'documents'),
		'taxonomies'  => array('project_state'),
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

// Register Widgets
add_action('widgets_init', 'pms_widget');
function pms_widget() {
	register_sidebar(array(
		'name'		=> esc_html__('Sidebar-1', 'pms'),
		'id'			=> 'sidebar1',
		'description'	=> esc_html__('Add widgets here', 'pms'),
		'before_widget'	=> '<div id="%1$s" class="widget-1">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="text-dark"> ',
		'after_title'		=> '</h4>'
	));
	register_sidebar(array(
		'name'		=> esc_html__('Sidebar-2', 'pms'),
		'id'			=> 'sidebar2',
		'description'	=> esc_html__('Add widgets here', 'pms'),
		'before_widget'	=> '<div id="%1$s" class="widget-2">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="text-dark"> ',
		'after_title'		=> '</h4>'
	));
	register_sidebar(array(
		'name'		=> esc_html__('Sidebar-3', 'pms'),
		'id'			=> 'sidebar3',
		'description'	=> esc_html__('Add widgets here', 'pms'),
		'before_widget'	=> '<div id="%1$s" class="widget-3">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="text-dark"> ',
		'after_title'		=> '</h4>'
	));
}

// 새로운 포스트의 슬러그를 'YYMM-중복 체크된 연번'로 설정
function custom_force_default_post_slug( $data ) {
    if ( empty( $data['post_name'] ) ) {
        global $wpdb;
        $post_count = $wpdb->get_var( "SELECT MAX(SUBSTRING_INDEX(post_name, '-', -1)) FROM $wpdb->posts WHERE post_status IN ('publish', 'draft', 'pending', 'private', 'trash')" );
        $post_count = sprintf( "%03d", intval( $post_count ) + 1 );

        $new_post_slug = date('ym') . '-' . $post_count;
        $slug_exists = $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_name = %s", $new_post_slug ) );

        while ( $slug_exists ) {
            $post_count++;
            $post_count = sprintf( "%03d", $post_count );
            $new_post_slug = date('ym') . '-' . $post_count;
            $slug_exists = $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_name = %s", $new_post_slug ) );
        }

        $data['post_name'] = $new_post_slug;
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'custom_force_default_post_slug' );

// 한글 문서, 아웃룩 문서 upload 가능하게
add_filter( 'upload_mimes', function( $existing_mimes ) {
	$existing_mimes['hwp'] = 'application/hangul';
	$existing_mimes['hwpx'] = 'application/hangul';
	$existing_mimes['msg'] = 'application/vnd.ms-outlook';
	return $existing_mimes;
  } );

  // 확장자 추가 - 본문 미디어 파일
function add_extension_to_media_link($content) {
	$pattern = '/<a(.*?)href=["\'](.*?)["\'](.*?)>(.*?)<\/a>/i';
	$replacement = '<a$1href="$2" $3>$4</a>';
	$content = preg_replace_callback($pattern, function ($matches) {
	  $link_url = $matches[2];
	  $file_extension = pathinfo($link_url, PATHINFO_EXTENSION);
	  $link_text = $matches[4] . '.' . $file_extension;
	  return str_replace($matches[4], $link_text, $matches[0]);
	}, $content);
	return $content;
  }
  add_filter('the_content', 'add_extension_to_media_link');

  // 확장자 추가 - 첨부 미디어 파일
  function add_extension_to_media_title($title, $id) {
	$attachment = get_post($id);

	if ($attachment && $attachment->post_type === 'attachment') {
	  $file_url = wp_get_attachment_url($id);
	  $file_extension = pathinfo($file_url, PATHINFO_EXTENSION);

	  $title .= '.' . $file_extension;
	}

	return $title;
  }
  add_filter('the_title', 'add_extension_to_media_title', 10, 2);

// 성과물의 진도를 표시
function progress_state($post_id) {
    $document_progress = get_field('progress_state', $post_id, false);
    if (empty($document_progress)) {
        $document_progress = '';
    } elseif ($document_progress == '완료') {
        echo '<span class="float-right"><i class="fas fa-check fa-sm text-success"></i>';
    } elseif ($document_progress == '작성중') {
        echo '<span class="float-right"><i class="fas fa-hourglass-half fa-sm text-danger"></i></span>';
    } else { // '해당없음'
        echo '<span class="float-right"><i class="fas fa-ban fa-sm text-muted"></i></span>';
    };
}

// 성과물 & 카테고리
// function custom_taxonomy_template($template) {
//     if (is_tax('project_state') && is_post_type_archive('document')) {
//         // 템플릿 파일 경로를 지정
//         $custom_template = get_template_directory() . '/taxonomy-project_state_category-document.php';

//         // 해당 템플릿 파일이 존재하면 사용, 그렇지 않으면 기본 템플릿 사용
//         if (file_exists($custom_template)) {
//             return $custom_template;
//         }
//     }
//     return $template; // 기본 템플릿 파일 반환
// }
// add_filter('template_include', 'custom_taxonomy_template');

// function custom_term_link($taxonomy, $term, $post_type) {
//     $term_obj = get_term_by('slug', $term, $taxonomy);

//     if ($term_obj) {
//         return get_post_type_archive_link($post_type) . '?' . $taxonomy . '=' . $term_obj->slug;
//     }

//     return false;
// }

// function custom_query_by_post_type_and_taxonomy($post_type, $taxonomy, $term_slug) {
//     $args = array(
//         'post_type' => $post_type,
//         'tax_query' => array(
//             array(
//                 'taxonomy' => $taxonomy,
//                 'field' => 'slug',
//                 'terms' => $term_slug,
//             ),
//         ),
//     );

//     $query = new WP_Query($args);

//     return $query;
// }
// add_filter('post_type_archive_link', 'custom_post_type_archive_link', 10, 2);

// 포스트 타입에 맞는 택소노미 연결 url 생성 함수
function generate_tax_archive_link($term_value) {
    // Replace 'aaa-archive' with the actual archive page slug
    $archive_url = home_url('/documents/');

    // Append the tax value to the URL query parameters
    $link = add_query_arg('project_state', $term_value, $archive_url);

    return esc_url($link);
}
