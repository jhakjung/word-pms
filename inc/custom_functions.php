<?php

function redirect_to_login_on_home() {
    // 첫 화면(홈페이지)인지 확인
    if (is_front_page() && !current_user_can('manage_options') && !is_user_logged_in()) {
        wp_redirect(wp_login_url(home_url()));

        exit;
    }

}
add_action('template_redirect', 'redirect_to_login_on_home');

// 관리자가 아닌 경우 싱글페이지의 어드민바 숨기기
function hide_admin_bar_for_subscribers()
{
    if (!current_user_can('administrator')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_subscribers');


// 구독자 권한
function add_post_capabilities_to_subscriber() {
    $role = get_role('subscriber');
    if ($role) {
        // 글 작성 및 수정 권한
        $role->add_cap('edit_posts'); // 구독자가 글 작성 가능
        $role->add_cap('edit_published_posts'); // 작성한 글 수정 가능
        $role->add_cap('publish_posts'); // 글 발행 가능

        // 글 삭제 권한 추가
        $role->add_cap('delete_posts'); // 구독자가 자신의 글 삭제 가능
        $role->add_cap('delete_published_posts'); // 발행된 자신의 글 삭제 가능
    }
}
add_action('init', 'add_post_capabilities_to_subscriber');

//<!-------------즐겨찾기 관련 구현-------------
// 즐겨찾기 택소노미 추가
function add_favorites_taxonomy() {
    register_taxonomy(
        'favorite', // Taxonomy 이름
        array('post', 'page'), // Post Type
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
        array('post', 'page'), // 대상 포스트 유형
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
//---------------즐겨찾기 관련 구현----------!>



// ************************ //
// ***** 테이블 삽입 ******* //
// ************************ //

// '테이블 추가' 버튼 추가
add_action('media_buttons', 'add_table_insert_button');

function add_table_insert_button() {
    // '미디어 추가' 버튼 옆에 '테이블 추가' 버튼 생성
    echo '<button type="button" id="insert_table_button" class="button">테이블 추가</button>';
}

// 스크립트 로드
function enqueue_excel_to_editor_script() {
    // 테이블 추가용 JavaScript
    wp_enqueue_script(
        'excel-to-editor-script',
        get_template_directory_uri() . '/js/excel-to-editor.js', // JS 파일 경로
        array('jquery'),
        null,
        true
    );

    // XLSX 라이브러리 로드
    wp_enqueue_script(
        'xlsx',
        'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js', // XLSX 라이브러리
        array(),
        null,
        true
    );
}
add_action('admin_enqueue_scripts', 'enqueue_excel_to_editor_script');

function enqueue_frontend_filter_script() {
    wp_enqueue_script(
        'table-filter-script',
        get_template_directory_uri() . '/js/table-filter.js', // 위의 필터링 코드 파일 경로
        array('jquery'),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_frontend_filter_script');

// wp_kses 필터 제거 (HTML 필터링 비활성화)
remove_filter('content_save_pre', 'wp_filter_post_kses');
remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');

// WordPress에서 <style> 태그 허용
function allow_style_tags($init_array) {
    if (!empty($init_array['extended_valid_elements'])) {
        $init_array['extended_valid_elements'] .= ',style[type|media]';
    } else {
        $init_array['extended_valid_elements'] = 'style[type|media]';
    }
    return $init_array;
}
add_filter('tiny_mce_before_init', 'allow_style_tags');

function load_custom_table_styles() {
    wp_enqueue_style('custom-table-style', get_template_directory_uri() . '/assets/styles/table-style.css');
}
add_action('wp_enqueue_scripts', 'load_custom_table_styles');

function redirect_to_post_after_save($post_id) {
    // 자동 저장일 때는 리디렉션하지 않음
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // 새 글 작성 중에는 리디렉션하지 않도록
    if (isset($_POST['post_status']) && $_POST['post_status'] == 'auto-draft') {
        return;
    }

    // 관리 화면에서 새 글을 작성할 때는 리디렉션하지 않도록
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        return; // 수정 모드일 때만 리디렉션
    }

    // 포스트가 정상적으로 저장되었을 때만
    if (get_post_type($post_id) == 'post') {
        // 리디렉션 URL을 포스트 편집 페이지로 설정
        $edit_url = get_admin_url(null, 'post.php?post=' . $post_id . '&action=edit');
        wp_redirect($edit_url); // 편집 페이지로 리디렉션
        exit;
    }
}
add_action('save_post', 'redirect_to_post_after_save');



