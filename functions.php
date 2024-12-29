<?php
require get_template_directory() . '/inc/post-meta.php';
require get_template_directory() . '/inc/taxonomy-group.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/inc/comment-template.php';
require get_template_directory() . '/inc/custom_functions.php';
// require get_template_directory() . '/inc/custom_ui_functions.php';
// require get_template_directory() . '/inc/category_functions.php';

// Theme resource FIles
function enqueue_custom_scripts() {
	wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
	wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
	wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
	wp_enqueue_script('custom-js', get_theme_file_uri('/assets/scripts/custom.js'), array('jquery'), '1.0', true);
	wp_enqueue_style('my-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

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


// 한글 문서 업로드 가능하게
function my_custom_mime_types( $mimes ) {

	// New allowed mime types - 새롭게 허용하는 mime 타입
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	$mimes['hwp'] = 'application/hangul';
	$mimes['hwpx'] = 'application/hangul';
	$mimes['msg'] = 'application/vnd.ms-outlook';
	$mimes['svg'] = 'application/msedge';
	$mimes['dat'] = 'application/wordpad';
	$mimes['dwg'] = 'application/cad';
	$mimes['stp'] = 'application/cad';
    $mimes['ico'] = 'image/vnd.microsoft.icon'; // .ico 파일의 MIME 유형 추가
    $mimes['ppt'] = 'application/vnd.ms-powerpoint';

    // 수정된 pptx MIME 타입
    $mimes['pptx'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';

	// Optional. Remove a mime type. - (선택 사항) mime type 제거
	unset( $mimes['exe'] );

	return $mimes;
}
add_filter( 'upload_mimes', 'my_custom_mime_types' );


// 모바일에서 admin bar 숨기기
function hide_admin_bar_on_mobile() {
    if (wp_is_mobile()) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('wp_loaded', 'hide_admin_bar_on_mobile');

// 올바른 위치: init 액션 후에 번역을 로드
function custom_load_theme_translations() {
    load_theme_textdomain('your-theme-textdomain', get_template_directory() . '/languages');
}
add_action('init', 'custom_load_theme_translations', 10);
