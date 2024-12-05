<?php

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

// favicon.ico 업로드 가능하게
function allow_favicon_upload($mime_types)
{
    $mime_types['ico'] = 'image/vnd.microsoft.icon'; // .ico 파일의 MIME 유형 추가
    return $mime_types;
}
add_filter('upload_mimes', 'allow_favicon_upload');

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
        wp_set_post_categories($post_id, array_merge($categories, $parent_categories));
    }
}
add_action('save_post', 'add_parent_category_on_save');


function redirect_to_login_on_home() {
    // 첫 화면(홈페이지)인지 확인
    if (is_front_page() && !current_user_can('manage_options') && !is_user_logged_in()) {
        wp_redirect(wp_login_url(home_url()));

        exit;
    }

}
add_action('template_redirect', 'redirect_to_login_on_home');

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

// 관리자가 아닌 경우 어드민바 숨기기
function hide_admin_bar_for_subscribers()
{
    if (!current_user_can('administrator')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_subscribers');

function sort_acf_category_field_desc($field) {
    // 모든 카테고리 가져오기
    $categories = get_categories(array(
        'hide_empty' => false, // 비어 있는 카테고리 포함
        'orderby' => 'name',  // 이름으로 정렬
        'order' => 'DESC'     // 내림차순
    ));

    // ACF 라디오 버튼에 선택 항목 설정
    $field['choices'] = [];
    foreach ($categories as $category) {
        $field['choices'][$category->term_id] = $category->name;
    }

    return $field;
}
add_filter('acf/load_field/name=category', 'sort_acf_category_field_desc');



// 부모 카테고리 선택 불가
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

            // 카테고리 정렬: 내림차순
            const list = document.querySelector("ul.acf-checkbox-list");
            if (list) {
                const items = Array.from(list.children);
                items.sort((a, b) => {
                    const textA = a.textContent.trim().toLowerCase();
                    const textB = b.textContent.trim().toLowerCase();
                    return textB.localeCompare(textA); // 내림차순 정렬
                });

                // 정렬된 항목을 다시 추가
                items.forEach(item => list.appendChild(item));
            }
        });
    </script>';
}
add_action('admin_head', 'custom_admin_head_script');


// 자식 카테고리만 저장
function save_only_child_category($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (get_post_type($post_id) !== 'post') {
        return;
    }

    // ACF에서 선택된 카테고리 ID 가져오기
    $selected_category = get_field('category', $post_id);

    if ($selected_category) {
        $category = get_category($selected_category);

        // 부모 카테고리인 경우 저장하지 않음
        if ($category->parent === 0) {
            wp_set_post_categories($post_id, []); // 부모 카테고리 제거
        } else {
            wp_set_post_categories($post_id, [$selected_category]); // 자식 카테고리 저장
        }
    }
}
add_action('save_post', 'save_only_child_category');

function hide_admin_notices() {
    echo '<style>
        #wpbody-content .update-nag,
        #wpbody-content .notice,
        .notice-error,
        .notice-success,
        .notice-warning {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'hide_admin_notices');

// 관리자 화면에서 글 메뉴만 보이게
function restrict_admin_menu_for_subscribers()
{
    if (is_admin()) {
        if (!current_user_can('administrator')) {
            remove_menu_page('index.php'); // 대시보드
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

// 구독자의 경우 자신의 글 목록만 볼 수 있게
function restrict_posts_to_own($query)
{
    // 관리자 화면 & 글(post) 목록에서만 적용
    if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'post') {
        $user = wp_get_current_user();

        // 구독자인 경우 자신의 게시물만 표시
        if (!current_user_can('administrator')) {
            $query->set('author', $user->ID); // 자신의 게시물만 쿼리
        }
    }
}
add_action('pre_get_posts', 'restrict_posts_to_own');

// 메뉴 이름을 '내 글 목록'으로 변경
function change_menu_label_for_subscriber()
{
    if (!current_user_can('administrator')) {
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

// 미디어 추가 --> 파일 추가
// function customize_editor_for_subscribers()
// {
//     if (!current_user_can('administrator')) {
//         echo '<style>
//             /* 비주얼 탭 숨기기 */
//             #content-tmce, /* 비주얼 탭 버튼 */
//             #wp-content-editor-tools .wp-editor-tabs span { display: none !important; }

//             /* 입력 박스 맨 아래 상태 정보 숨기기 */
//             #wp-content-wrap .wp-editor-meta { display: none !important; }
//         </style>';

//         echo '<script type="text/javascript">
//             jQuery(document).ready(function($) {
//                 /* "미디어 추가" 버튼의 텍스트를 "파일 추가"로 변경 */
//                 $("#insert-media-button").attr("title", "파일 추가").text("파일 추가");

//                 /* 텍스트 탭만 활성화 (비주얼 탭 강제 비활성화) */
//                 if ($("#content-tmce").hasClass("active")) {
//                     $("#content-tmce").removeClass("active");
//                     $("#content-html").addClass("active");
//                     $("#wp-content-wrap").removeClass("tmce-active").addClass("html-active");
//                 }
//             });

//         </script>';


//     }
// }
// add_action('admin_head-post.php', 'customize_editor_for_subscribers');
// add_action('admin_head-post-new.php', 'customize_editor_for_subscribers');


function hide_slugdiv_for_subscribers_css() {
    if (current_user_can('subscriber')) {
        echo '<style>#slugdiv { display: none !important; }</style>';
    }
}
add_action('admin_head', 'hide_slugdiv_for_subscribers_css');


// 태그 메뉴 숨기기
function remove_specific_submenu_items() {
    // 관리자가 아닌 경우에만 실행
    if (!current_user_can('administrator')) {
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

function remove_editor_toolbar_space() {
    echo '<style>
        #ed_toolbar {
            position: absolute !important; /* 텍스트 영역에 영향을 주지 않도록 */
            top: -9999px !important; /* 화면에서 완전히 숨김 */
            left: -9999px !important;
        }
        #wp-content-editor-container {
            margin-top: 0 !important; /* 여백을 제거 */
        }
    </style>';
}
add_action('admin_head', 'remove_editor_toolbar_space');

function fix_editor_toolbar_and_content() {
    echo '<style>
        #ed_toolbar {
            display: none !important; /* 툴바를 완전히 숨김 */
        }
        #wp-content-editor-container {
            padding-top: 0 !important; /* 텍스트 필드의 위쪽 여백 제거 */
            margin-top: 0 !important;
        }
    </style>';
}
add_action('admin_head', 'fix_editor_toolbar_and_content');



// 요소 뒤의 텍스트 숨기기
function hide_text_after_elements() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            // 텍스트 노드를 숨길 대상 배열
            var targets = [
                document.getElementById("post-lock-dialog"), // #post-lock-dialog
                document.querySelector("hr.wp-header-end")  // <hr class="wp-header-end">
            ];

            // 대상 요소 각각에 대해 처리
            targets.forEach(function(target) {
                if (target) {
                    var nextNode = target.nextSibling; // 다음 형제 노드 가져오기
                    if (nextNode && nextNode.nodeType === Node.TEXT_NODE) {
                        nextNode.textContent = ""; // 텍스트 내용 제거
                    }
                }
            });
        });
    </script>';
}
add_action('admin_head', 'hide_text_after_elements');


// HTML 필터링 제거 및 텍스트 입력기에서 HTML 지원
function disable_tinymce_and_allow_html_for_subscriber($default) {
    if (current_user_can('subscriber')) {
        // TinyMCE 비활성화
        add_filter('user_can_richedit', '__return_false', 10);

        // 저장 시 HTML 필터링 제거
        remove_filter('content_save_pre', 'wp_kses_post');
        remove_filter('content_filtered_save_pre', 'wp_kses_post');

        // 모든 HTML 태그 허용
        add_filter('wp_kses_allowed_html', function ($allowed_tags, $context) {
            return 'post';
        }, 10, 2);

        // wpautop 필터 제거
        remove_filter('the_content', 'wpautop');
        remove_filter('the_excerpt', 'wpautop');
    }
    return $default;
}
add_filter('user_can_richedit', 'disable_tinymce_and_allow_html_for_subscriber');

// 1. <style> 및 <script> 태그 허용
function allow_style_and_script_tags($tags, $context) {
    if ($context === 'post') {
        $tags['style'] = array();
        $tags['script'] = array(
            'type' => true,
            'src' => true,
        );
    }
    return $tags;
}
add_filter('wp_kses_allowed_html', 'allow_style_and_script_tags', 10, 2);

// 2. 저장 시 HTML 필터 제거
function allow_style_and_script_on_save($data, $postarr) {
    if (current_user_can('subscriber')) {
        remove_filter('content_save_pre', 'wp_kses_post');
        remove_filter('content_filtered_save_pre', 'wp_kses_post');
    }
    return $data;
}
add_filter('wp_insert_post_data', 'allow_style_and_script_on_save', 10, 2);

// 3. 구독자에게 unfiltered_html 권한 추가
function add_unfiltered_html_capability() {
    $role = get_role('subscriber');
    if ($role) {
        $role->add_cap('unfiltered_html');
    }
}
add_action('init', 'add_unfiltered_html_capability');

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

add_filter('the_content', function ($content) {
    if (strpos($content, '<table') !== false) {
        remove_filter('the_content', 'wpautop');
    }
    return $content;
}, 9);

// 구독자는 항상 TinyMCE를 사용할 수 있도록 설정
function always_enable_tinymce_for_subscribers($default) {
    if (current_user_can('subscriber')) {
        return true;  // TinyMCE 활성화
    }
    return $default;
}
add_filter('user_can_richedit', 'always_enable_tinymce_for_subscribers');

// 편집기 기본값을 TinyMCE로 설정
function set_default_editor_to_tinymce_for_subscribers($editor) {
    if (current_user_can('subscriber')) {
        return 'tinymce';  // 기본 편집기를 TinyMCE로 설정
    }
    return $editor;
}
add_filter('wp_default_editor', 'set_default_editor_to_tinymce_for_subscribers');
