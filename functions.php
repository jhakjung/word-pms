<?php
require get_template_directory() . '/inc/post-meta.php';
require get_template_directory() . '/inc/favorite.php';
require get_template_directory() . '/inc/taxonomy-group.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/inc/comment-template.php';

// Theme resource FIles
function enqueue_custom_scripts() {
	wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
	wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
	wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
	wp_enqueue_script('custom-js', get_theme_file_uri('/assets/scripts/custom.js'), array('jquery'), '1.0', true);
	wp_enqueue_style('my-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// // 성과물 포스트 타입 등록
// function document_post_types() {
// 	register_post_type('document', array(
// 		'show_in_rest' => true,
// 		'capability_type' => 'document',
// 		'map_meta_cap'  => true,
// 		'supports' => array('title', 'editor', 'comments', 'author', 'excerpt'),
// 		'rewrite' => array('slug' => 'documents'),
// 		'taxonomies'  => array('doc_project_state'),
// 		'has_archive' => true,
// 		'public' => true,
// 		'labels' => array(
// 			'name' => '성과물',
// 			'add_new_item' => '성과물 추가',
// 			'edit_item' => '성과물 수정',
// 			'all_items' => '성과물 목록',
// 			'singular_name' => '성과물' ),
// 		'menu_icon' => 'dashicons-media-document'
// 	));
// }
// add_action('init', 'document_post_types');

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
    // if ( 'post' === $data['post_type'] && empty( $data['post_name'] ) ) { : 포스트 뿐만 아니라 페이지도 동일하게 연번 생성
	if ( empty( $data['post_name'] ) ) {
        global $wpdb;
        $post_count = $wpdb->get_var( "SELECT MAX(SUBSTRING_INDEX(post_name, '-', -1)) FROM $wpdb->posts WHERE post_status IN ('publish', 'draft', 'pending', 'private', 'trash') AND post_type = 'post'" );
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
	$existing_mimes['svg'] = 'application/msedge';
	$existing_mimes['dat'] = 'application/wordpad';
	$existing_mimes['dwg'] = 'application/cad';
	$existing_mimes['stp'] = 'application/cad';
	return $existing_mimes;
  } );

// 확장자 추가 - 본문 미디어 파일 (Old)
// function add_extension_to_media_link($content) {
// 	$pattern = '/<a(.*?)href=["\'](.*?)["\'](.*?)>(.*?)<\/a>/i';
// 	$replacement = '<a$1href="$2" $3>$4</a>';
// 	$content = preg_replace_callback($pattern, function ($matches) {
// 	  $link_url = $matches[2];
// 	  $file_extension = pathinfo($link_url, PATHINFO_EXTENSION);
// 	  $link_text = $matches[4] . '.' . $file_extension;
// 	  return str_replace($matches[4], $link_text, $matches[0]);
// 	}, $content);
// 	return $content;
//   }
//   add_filter('the_content', 'add_extension_to_media_link');


// 확장자 추가 - 본문 미디어 파일 (New 1.0)
//  function add_extension_to_media_link_text($content) {
//    // 정규 표현식을 사용하여 본문 내에서 이미지 링크를 찾습니다.
//    $pattern = '/<a(.*?)href=[\'|"](.*?)(.[a-zA-Z0-9]+)[\'|"](.*?)>(.*?)<\/a>/i';
//
//    // 정규 표현식에 일치하는 모든 이미지 링크를 찾아 함수를 적용합니다.
//    $content = preg_replace_callback($pattern, function($matches) {
//        $link = $matches[0]; // 전체 링크 태그
//        $url = $matches[2]; // 이미지 파일 URL
//        $extension = $matches[3]; // 확장자
//
//        // 이미지 파일의 확장자를 링크 텍스트에 추가합니다.
//        $new_link = str_replace($link, '<a' . $matches[1] . 'href="' . $url . $extension . '"' . $matches[4] . '>' . $matches[5] . $extension . '</a>', $link);
//
//        return $new_link;
//    }, $content);
//
//    return $content;
//}

// 확장자 추가 - 본문 미디어 파일 (New 1.1)
function add_extension_to_media_link_text($content) {
    // 본문에서 모든 링크를 찾습니다.
    $pattern = '/<a(.*?)href=["\'](https?:\/\/[^"\'\s]+)(\.\w{2,4})([^"\'\s]*)["\'](.*?)>(.*?)<\/a>/i';

    // 정규 표현식에 일치하는 모든 링크를 찾아 함수를 적용합니다.
    $content = preg_replace_callback($pattern, function($matches) {
        $url = $matches[2]; // 이미지 파일 URL
        $extension = $matches[3]; // 확장자
        $text = $matches[6]; // 링크 텍스트

        // 이미지 파일의 확장자를 링크 텍스트에 추가합니다.
        $new_link = '<a' . $matches[1] . 'href="' . $url . $extension . $matches[4] . '"' . $matches[5] . '>' . $text . $extension . '</a>';

        return $new_link;
    }, $content);

    return $content;
}
add_filter('the_content', 'add_extension_to_media_link_text');


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
// function progress_state($post_id) {
//     $document_progress = get_field('progress_state', $post_id, false);
//     if (empty($document_progress)) {
//         $document_progress = '';
//     } elseif ($document_progress == '완료') {
//         echo '<span class="float-right"><i class="fas fa-check fa-sm text-success"></i>';
//     } elseif ($document_progress == '작성중') {
//         echo '<span class="float-right"><i class="fas fa-hourglass-half fa-sm text-danger"></i></span>';
//     } else { // '해당없음'
//         echo '<span class="float-right"><i class="fas fa-ban fa-sm text-muted"></i></span>';
//     };
// }

// 포스트 타입에 맞는 택소노미 연결 url 생성 함수
function generate_tax_archive_link($taxonomy, $term_value) {
    $archive_url = home_url('/documents/');
    // Append the tax value to the URL query parameters
    $link = add_query_arg($taxonomy, $term_value, $archive_url);
    return esc_url($link);
}

// 로그인 페이지 CSS 적용
function ourLoginCSS() {
	wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
	wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
	wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
	wp_enqueue_style('my-style', get_stylesheet_uri());
}
add_action('login_enqueue_scripts', 'ourLoginCSS');

// 로그인 페이지의 로고를 사이트 이름으로 변경
/** 아래 코드로 대체 */
function custom_login_headertext() {
    echo '<h1 class="login"><a href="' . esc_url( home_url() ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '">' . get_bloginfo( 'name', 'display' ) . '</a></h1>';
}
add_action( 'login_headertext', 'custom_login_headertext' );


// 로고를 누르면 홈으로 이동하게
function ourHeaderUrl() {
	return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'ourHeaderUrl');

//
function redirect_after_login($username, $user) {
    $allowed_roles = array('subscriber', 'manager', 'author'); // 원하는 역할 추가

    if (array_intersect($allowed_roles, $user->roles)) {
        wp_redirect(home_url()); // 원하는 리디렉션 URL로 변경 가능
        exit();
    }
}
add_action('wp_login', 'redirect_after_login', 10, 2);



// 구독자인 경우 어드민바 No Show
add_action('init', function() {
	if (current_user_can('pm')) {
	  show_admin_bar(true);
	} if (current_user_can('subscriber')) {
		show_admin_bar(false);
}});


// a 태그 다음에 라인 추가 ==> 댓글의 첨부파일에도 적용이 되어 폐기함
// function auto_insert_br_after_a_tags($content) {
//     // 정규식을 사용하여 <a> 태그 다음에 <br> 태그를 추가
//     $pattern = '/<\/a>/i';
//     $replacement = '</a><br>';
//     $content = preg_replace($pattern, $replacement, $content);

//     return $content;
// }
// add_filter('the_content', 'auto_insert_br_after_a_tags');

// 관리자를 제외하고는 "카테고리" 편집메뉴 No Show
function hide_category_menu_for_non_admins() {
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    if (in_array('administrator', $user_roles)) {
        return;
    }
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
}
add_action('admin_menu', 'hide_category_menu_for_non_admins');

// 한글 문서 업로드 가능하게
function my_custom_mime_types( $mimes ) {

	// New allowed mime types - 새롭게 허용하는 mime 타입
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	$mimes['hwp'] = 'application/hangul';

	// Optional. Remove a mime type. - (선택 사항) mime type 제거
	unset( $mimes['exe'] );

	return $mimes;
}
add_filter( 'upload_mimes', 'my_custom_mime_types' );

// 에디터 화면에서 카테고리 안 보이게
function hide_category_metabox() {
	remove_meta_box('categorydiv', 'post', 'side');
}

add_action('admin_menu', 'hide_category_metabox');

// 모바일에서 admin bar 숨기기
function hide_admin_bar_on_mobile() {
    if (wp_is_mobile()) {
        add_filter('show_admin_bar', '__return_false');
    }
}

add_action('wp_loaded', 'hide_admin_bar_on_mobile');

// 즐겨찾기 택소노미 추가
function add_favorites_taxonomy() {
    register_taxonomy(
        'favorite', // Taxonomy 이름
        'post', // Post Type
        array(
            'labels' => array(
                'name' => '즐겨찾기',
                'singular_name' => '즐겨찾기',
                'search_items' => '즐겨찾기 검색',
                'all_items' => '모든 즐겨찾기',
                'edit_item' => '즐겨찾기 수정',
                'update_item' => '즐겨찾기 업데이트',
                'add_new_item' => '새 즐겨찾기 추가',
                'new_item_name' => '새 즐겨찾기 이름',
                'menu_name' => '즐겨찾기',
            ),
            'hierarchical' => false, // 카테고리처럼 계층적이지 않음
            'public' => false, // 사용자 정의 관리 화면에 노출되지 않음
            'show_ui' => false, // 기본 관리 화면 비활성화
        )
    );
}
add_action('init', 'add_favorites_taxonomy');

// 체크박스 메타박스 표시
function add_favorite_checkbox_meta_box() {
    add_meta_box(
        'favorite_checkbox', // 메타 박스 ID
        '즐겨찾기 등록', // 메타 박스 제목
        'render_favorite_checkbox', // 콜백 함수
        'post', // 대상 포스트 유형
        'normal', // 위치 (제목 아래)
        'high' // 우선순위
    );
}
add_action('add_meta_boxes', 'add_favorite_checkbox_meta_box');

// 체크박스 렌더링 함수
function render_favorite_checkbox($post) {
    // 기존 즐겨찾기 상태를 가져옴
    $is_favorite = has_term('즐겨찾기', 'favorite', $post->ID);

    // 체크박스 출력
    echo '<label>';
    echo '<input type="checkbox" name="favorite_checkbox" value="1" ' . checked($is_favorite, true, false) . '>';
    echo ' 즐겨찾기';
    echo '</label>';
}

function save_favorite_checkbox($post_id) {
    // 자동 저장 방지
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 권한 확인
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // 체크박스 값 확인
    if (isset($_POST['favorite_checkbox']) && $_POST['favorite_checkbox'] == '1') {
        // "즐겨찾기" 택소노미 추가
        wp_set_post_terms($post_id, array('즐겨찾기'), 'favorite', false);
    } else {
        // "즐겨찾기" 택소노미 제거
        wp_remove_object_terms($post_id, '즐겨찾기', 'favorite');
    }
}
add_action('save_post', 'save_favorite_checkbox');

