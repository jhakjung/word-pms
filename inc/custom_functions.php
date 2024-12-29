<?php

function redirect_to_login_on_home() {
    // 첫 화면(홈페이지)인지 확인
    if (is_front_page() && !current_user_can('administrator') && !is_user_logged_in()) {
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


// 구독자 또는 기여자 권한 추가
function add_post_capabilities_to_subscriber_and_contributor() {
    // 구독자 역할
    $subscriber_role = get_role('subscriber');
    if ($subscriber_role) {
        // 글 작성 및 수정 권한
        $subscriber_role->add_cap('edit_posts'); // 구독자가 글 작성 가능
        $subscriber_role->add_cap('edit_published_posts'); // 작성한 글 수정 가능
        $subscriber_role->add_cap('publish_posts'); // 글 발행 가능

        // 글 삭제 권한 추가
        $subscriber_role->add_cap('delete_posts'); // 구독자가 자신의 글 삭제 가능
        $subscriber_role->add_cap('delete_published_posts'); // 발행된 자신의 글 삭제 가능
    }

    // 기여자 역할
    $contributor_role = get_role('contributor');
    if ($contributor_role) {
        // 글 작성 및 수정 권한
        $contributor_role->add_cap('edit_posts'); // 기여자가 글 작성 가능
        $contributor_role->add_cap('edit_published_posts'); // 작성한 글 수정 가능
        $contributor_role->add_cap('publish_posts'); // 글 발행 가능

        // 글 삭제 권한 추가
        $contributor_role->add_cap('delete_posts'); // 기여자가 자신의 글 삭제 가능
        $contributor_role->add_cap('delete_published_posts'); // 발행된 자신의 글 삭제 가능
    }
}
add_action('init', 'add_post_capabilities_to_subscriber_and_contributor');


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

// 어드민 환경에서 광고 팝업 제거
function remove_editor_notices() {
    remove_all_actions('admin_notices'); // 관리자 공지 제거
    remove_all_actions('all_admin_notices'); // 모든 관리자 공지 제거
}
add_action('admin_init', 'remove_editor_notices');

//
function change_insert_media_button_text() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var mediaButton = document.getElementById('insert-media-button');
            if (mediaButton) {
                mediaButton.textContent = '파일 추가'; // 텍스트를 '파일 추가'로 변경
            }
        });
    </script>
    <?php
}
add_action('admin_head', 'change_insert_media_button_text');

function hide_admin_bar_comments()
{
        /*post-preview, /* 미리보기 버튼 숨기기 */
        /*.misc-pub-section, /* 상태, 가시성, 즉시발행 숨기기 */
        #minor-publishing, /* 공개 박스 하단 숨기기 */
    if(current_user_can('subscriber') || current_user_can('contributor')) {
        echo '<style>
        #screen-options-link-wrap, /* 화면 옵션 */
        #save-post, /* 임시글로 저장 버튼 숨기기 */
        .misc-pub-section.misc-pub-post-status, /* 상태: 임시글 편집 */
        .misc-pub-section.curtime.misc-pub-curtime, /* 즉시 발행 편집 */
        #contextual-help-link-wrap, /* 도움말 메뉴 */
        #postexcerpt.postbox, /* 요약글 입력 */
        #postimagediv, /* 특성이미지 설정 탭 숨기기 */
        #edit-slug-box, /* 고유주소 편집 라인 숨기기 */
        #category-add-toggle, /* 새 카테고리 추가 */
        .postbox-header .handle-actions.hide-if-no-js,
        div#local-storage-notice.notice.is-dismissible,
        #post-status-info, #submitdiv .postbox-header, #wpfooter,
        #wpbody-content .update-nag, #wpbody-content .notice,
        #slugdiv, .notice-error, .notice-success, .notice-warning,
        #toplevel_page_edit-post_type-acf-field-group,
        #toplevel_page_limit-login-attempts,
        #toplevel_page_ivory-search,
        #toplevel_page_members,
        #wp-admin-bar-comments,
        #wp-admin-bar-new-content,
        #wp-admin-bar-top-secondary,
        #wp-admin-bar-llar-root,
        #postexcerpt .inside p,
        span.order-higher-indicator,
        span.order-lower-indicator,
        span.toggle-indicator
        { display: none; }
        #major-publishing-actions { background: transparent; }
        </style>';
    }
}
add_action('admin_head', 'hide_admin_bar_comments');


// 관리자 화면에서 글 메뉴만 보이게
function restrict_admin_menu_for_subscribers()
{
    if (is_admin()) {
        // if (!current_user_can('administrator')) {
    if(current_user_can('subscriber') || current_user_can('contributor')) {
            remove_menu_page('index.php'); // 대시보드
            remove_menu_page('edit.php?post_type=page'); // 페이지
            remove_menu_page('edit.php?post_type=page'); // 페이지
            remove_menu_page('edit-comments.php'); // 댓글
            remove_menu_page('themes.php'); // 테마
            remove_menu_page('plugins.php'); // 플러그인
            remove_menu_page('upload.php'); // 미디어
            remove_menu_page('users.php'); // 사용자
            remove_menu_page('tools.php'); // 도구
            remove_menu_page('options-general.php'); // 설정
            remove_menu_page('profile.php'); // 프로필 메뉴 숨기기
            remove_menu_page('edit-tags.php?taxonomy=post_tag');
            remove_submenu_page('edit.php', 'order-post-types-post');
            remove_submenu_page('edit.php', 'to-interface-post');


            // 2. "메뉴 접기" 제거를 위한 JavaScript 추가
            add_action('admin_footer', function () {
                ?>
                <script type="text/javascript">
                    document.addEventListener('DOMContentLoaded', function() {
                        // "메뉴 접기" 버튼 숨기기
                        var collapseButton = document.getElementById('collapse-button');
                        if (collapseButton) {
                            collapseButton.style.display = 'none';
                        }
                    });
                </script>
                <?php
});
        }
    }
}
add_action('admin_menu', 'restrict_admin_menu_for_subscribers', 99);

// 메뉴 이름을 '내 글 목록'으로 변경
function change_menu_label_for_subscriber()
{
    // if (!current_user_can('administrator')) {
    if(current_user_can('subscriber') || current_user_can('contributor')) {
        global $menu;
        foreach ($menu as &$item) {
            if ($item[0] === '글') {
                $item[0] = '내 글 목록'; // 메뉴 이름 변경
                break;
            }
        }
    }
}
add_action('admin_menu', 'change_menu_label_for_subscriber', 999);

// 태그 메뉴 숨기기
function remove_specific_submenu_items() {
    // 관리자가 아닌 경우에만 실행
    // if (!current_user_can('administrator')) {
    if(current_user_can('subscriber') || current_user_can('contributor')) {
        global $submenu;

        // 글(Posts) 메뉴의 하위 항목
        if (isset($submenu['edit.php'])) {
            // "태그" 메뉴 제거
            foreach ($submenu['edit.php'] as $key => $item) {
                if ($item[2] === 'edit-tags.php?taxonomy=post_tag') {
                    unset($submenu['edit.php'][$key]);
                }
            }
        }
    }
}
add_action('admin_menu', 'remove_specific_submenu_items', 999);

function disable_local_storage_notice($notices) {
    if (isset($notices['local_storage_notice'])) {
        unset($notices['local_storage_notice']);
    }
    return $notices;
}
add_filter('admin_notices', 'disable_local_storage_notice', 10, 1);

function remove_local_storage_notice() {
    // wp_localize_script로 전달된 데이터에서 알림을 제거
    wp_deregister_script('autosave');
}
add_action('admin_enqueue_scripts', 'remove_local_storage_notice');

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

// 에디터 화면에서 카테고리 안 보이게
function hide_category_metabox() {
	remove_meta_box('categorydiv', 'post', 'side');
}
add_action('admin_menu', 'hide_category_metabox');

function save_custom_categories($post_id) {
    // 자동 저장이 아닐 경우에만 실행
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 포스트 유형이 "post"일 경우
    if (get_post_type($post_id) == 'post') {
        $categories = get_the_terms($post_id, 'category');

        if ($categories && !is_wp_error($categories)) {
            // 기존 카테고리에서 ID를 가져옴
            $category_ids = array_map(function($category) {
                return $category->term_id;
            }, $categories);

            // 자식 카테고리일 경우 부모 카테고리도 추가
            foreach ($categories as $category) {
                if ($category->parent != 0) {
                    // 자식 카테고리의 부모 카테고리를 추가
                    $category_ids[] = $category->parent;
                }
            }

            // 중복된 카테고리 ID 제거
            $category_ids = array_unique($category_ids);

            // 카테고리 ID를 글에 설정
            wp_set_post_categories($post_id, $category_ids);

            // 선택된 카테고리 갱신을 강제로 트리거
            wp_cache_delete($post_id, 'post_meta');

            // 관리자가 카테고리 화면에서 새로고침되도록 강제하는 방법
            if (is_admin()) {
                wp_redirect(get_edit_post_link($post_id));
                exit;
            }
        }
    }
}
add_action('save_post', 'save_custom_categories');


// 자식 카테고리와 부모 카테고리 동시선택 불가
function custom_admin_head_script() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Custom JavaScript for admin head");

            // 부모 카테고리 라디오 버튼 숨기기
            document.querySelectorAll("ul.acf-checkbox-list > li").forEach(function(item) {
                if (item.querySelector("ul.children")) {
                    const input = item.querySelector("input[type=\'radio\']");
                    if (input) {
                        input.style.display = "none"; // 라디오 버튼 숨기기
                    }
                }
            });
        });
    </script>';
}
add_action('admin_head', 'custom_admin_head_script');

// 자식 카테고리 저장 시 부모 카테고리 자동저장
function add_parent_category_on_save($post_id) {
    // 확인: 자동 저장이나 수정일 경우 동작하지 않음
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 확인: 현재 사용자가 글 편집 권한이 있는지 확인
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // 확인: 게시물 유형이 'post'인지 확인
    if (get_post_type($post_id) !== 'post') {
        return;
    }

    // 현재 글에 지정된 카테고리 가져오기
    $categories = wp_get_post_categories($post_id);

    // 부모 카테고리를 저장할 배열
    $parent_categories = [];

    foreach ($categories as $category_id) {
        $parent_id = get_category($category_id)->parent;

        // 부모 카테고리가 있을 경우 배열에 추가
        if ($parent_id && !in_array($parent_id, $categories)) {
            $parent_categories[] = $parent_id;
        }
    }

    // 부모 카테고리를 기존 카테고리와 병합하여 저장
    if (!empty($parent_categories)) {
        $all_categories = array_merge($categories, $parent_categories);
        wp_set_post_categories($post_id, $all_categories);

        // 선택된 카테고리 갱신을 강제로 트리거
        wp_cache_delete($post_id, 'post_meta');

        // 관리자가 카테고리 화면에서 새로고침되도록 강제하는 방법
        if (is_admin()) {
            wp_redirect(get_edit_post_link($post_id));
            exit;
        }
    }
}
add_action('save_post', 'add_parent_category_on_save');

function display_categories_with_parent_and_child($class = 'badge badge__blue text-white') {
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

// 현재 페이지의 제목이 카테고리 이름인 경우 archive 리다이렉션
function redirect_page_to_category_archive() {
    // 현재 페이지의 제목이 카테고리 이름인 경우
    if (is_page()) {
        $page_title = get_the_title();

        // 카테고리 이름을 기준으로 카테고리 정보 가져오기
        $categories = get_categories([
            'hide_empty' => false, // 사용되지 않은 카테고리도 포함
            'name' => $page_title, // 카테고리 이름으로 검색
        ]);

        // 카테고리가 존재하는지 확인
        if ($categories && !empty($categories)) {
            $category = $categories[0]; // 첫 번째 카테고리 (이름이 일치하는 카테고리)
            $category_link = get_category_link($category->term_id); // 해당 카테고리의 아카이브 링크
            wp_redirect($category_link);
            exit; // 리다이렉트 후 코드 실행 중지
        } else {
            // 카테고리를 찾을 수 없으면 "페이지가 없습니다." 출력
            wp_die('페이지가 없습니다.');
        }
    }
}
add_action('template_redirect', 'redirect_page_to_category_archive');

// 암호 자료 표시
function custom_protected_title($title) {
    // "보호된 글: "로 시작하는 경우
    if (strpos($title, '보호된 글: ') === 0) {
        $lock = '<i class="fa fa-lock"></i>';
        // "보호된 글: "을 제거하고 " 🔒"을 추가
        $title = preg_replace('/^보호된 글: /', '', $title);
        $title .= ' 🔒';
        // $title .= ' '.$lock;

    }
    return $title;
}
add_filter('the_title', 'custom_protected_title');

// 포스트 삭제 시 미디어 파일도 삭제
function delete_attached_media_on_post_delete($post_id) {
    // 삭제된 포스트가 실제 포스트일 때만 실행
    if (get_post_type($post_id) != 'post') {
        return;
    }

    // 포스트에 첨부된 미디어 파일을 가져옵니다.
    $attachments = get_attached_media('', $post_id);

    // 첨부된 미디어 파일을 삭제합니다.
    foreach ($attachments as $attachment) {
        wp_delete_attachment($attachment->ID, true); // 두 번째 인자를 'true'로 설정하여 실제 파일을 삭제
    }
}
add_action('before_delete_post', 'delete_attached_media_on_post_delete');

// 태그 리스트 표출 최적화
function add_acf_custom_styles_admin() {
    echo '<style>
        #keyword .acf-taxonomy-field .categorychecklist-holder {
            display: flex;              /* 플렉스박스를 사용하여 항목들을 한 줄로 나열 */
            flex-wrap: wrap;            /* 항목들이 박스를 벗어나면 자동으로 줄바꿈 */
            gap: 10px;                  /* 항목 간의 간격을 설정 */
            max-height: none !important; /* 최대 높이를 설정하여 내용이 박스를 넘지 않게 설정 */
        }

        /* 각 리스트 항목을 인라인 블록으로 설정 */
        #keyword .acf-taxonomy-field .categorychecklist-holder li {
            display: inline-block;      /* 각 항목을 한 줄에 나열되도록 설정 */
            margin-right: 10px;         /* 항목 간의 간격을 설정 */
            white-space: nowrap;        /* 줄바꿈을 하지 않도록 설정 */
        }
    </style>';
}
add_action('acf/input/admin_enqueue_scripts', 'add_acf_custom_styles_admin');

