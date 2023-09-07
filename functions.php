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
		'capability_type' => 'document',
		'map_meta_cap'  => true,
		'supports' => array('title', 'editor', 'comments', 'author', 'excerpt'),
		'rewrite' => array('slug' => 'documents'),
		'taxonomies'  => array('doc_project_state'),
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

// 구독자가 로그인하면 홈으로
function redirectSubsToFrontend() {
	$ourCurrentUser = wp_get_current_user();
	if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
		wp_redirect(site_url('/'));
		exit;
	}
}
add_action('admin_init', 'redirectSubsToFrontend');

// 구독자인 경우 어드민바 No Show
add_action('init', function() {
	if (current_user_can('pm')) {
	  show_admin_bar(true);
	} if (current_user_can('subscriber')) {
		show_admin_bar(false);
	}
});

// 가입자 등록 확장
function custom_user_register_fields() {
	?>
  <p>
	<label for="user_bio"><?php _e('사용자 프로필', 'text-domain'); ?><br />
	  <textarea name="user_bio" id="user_bio" rows="5" cols="34" placeholder="소속과 전화번호 정보를 입력해야 승인됩니다."><?php echo (isset($_POST['user_bio'])) ? esc_textarea($_POST['user_bio']) : ''; ?></textarea>
	</label>
  </p>
  <?php
	}
	add_action('register_form', 'custom_user_register_fields');

// 가입자 등록 필드 유효성 검사
function custom_user_register_fields_validation($errors, $sanitized_user_login, $user_email) {
	if (empty($_POST['user_bio'])) {
		$errors->add('user_bio_error', __('사용자 프로필을 입력해주세요.', 'text-domain'));
	}
	return $errors;
}
add_filter('registration_errors', 'custom_user_register_fields_validation', 10, 3);

// 가입자 등록 정보 저장
function custom_user_register_fields_save($user_id) {
	if (!empty($_POST['user_bio'])) {
		update_user_meta($user_id, 'user_bio', sanitize_textarea_field($_POST['user_bio']));
	}
}

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