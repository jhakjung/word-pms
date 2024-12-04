<?php

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

// 2. TinyMCE 비활성화 (텍스트 입력기만 사용)
function disable_tinymce_for_subscriber($default) {
    if (current_user_can('subscriber')) {
        return false;
    }
    return $default;
}
add_filter('user_can_richedit', 'disable_tinymce_for_subscriber');

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
function customize_editor_for_subscribers()
{
    if (!current_user_can('administrator')) {
        echo '<style>
            /* 비주얼 탭 숨기기 */
            #content-tmce, /* 비주얼 탭 버튼 */
            #wp-content-editor-tools .wp-editor-tabs span { display: none !important; }

            /* 입력 박스 맨 아래 상태 정보 숨기기 */
            #wp-content-wrap .wp-editor-meta { display: none !important; }
        </style>';

        echo '<script type="text/javascript">
            jQuery(document).ready(function($) {
                /* "미디어 추가" 버튼의 텍스트를 "파일 추가"로 변경 */
                $("#insert-media-button").attr("title", "파일 추가").text("파일 추가");

                /* 텍스트 탭만 활성화 (비주얼 탭 강제 비활성화) */
                if ($("#content-tmce").hasClass("active")) {
                    $("#content-tmce").removeClass("active");
                    $("#content-html").addClass("active");
                    $("#wp-content-wrap").removeClass("tmce-active").addClass("html-active");
                }
            });

        </script>';


    }
}
add_action('admin_head-post.php', 'customize_editor_for_subscribers');
add_action('admin_head-post-new.php', 'customize_editor_for_subscribers');

// 불필요한 요소 모두 숨기기
function hide_admin_bar_comments()
{
    if (!current_user_can('administrator')) {
        echo '<style>
        #submitdiv .postbox-header,
        #wp-admin-bar-comments,
        #wp-admin-bar-new-content,
        #wp-admin-bar-top-secondary,
        #wp-admin-bar-llar-root,
        span.order-higher-indicator,
        span.order-lower-indicator,
        span.toggle-indicator,
        #wpfooter,
        #ed_toolbar,
        #ed_toolbar > input,
        .wp-editor-tabs,
        .mce-toolbar-grp,
        #post-status-info,
        #save-post, /* 임시글로 저장 버튼 숨기기 */
        #post-preview, /* 미리보기 버튼 숨기기 */
        .misc-pub-section, /* 상태, 가시성, 즉시발행 숨기기 */
        #minor-publishing, /* 공개 박스 하단 숨기기 */
        #contextual-help-link-wrap, /* 도움말 메뉴 */
        #screen-options-link-wrap, /* 화면 옵션 */
        #postimagediv, /* 특성이미지 설정 탭 숨기기 */
        #edit-slug-box, /* 고유주소 편집 라인 숨기기 */
        #category-add-toggle, /* 새 카테고리 추가 */
        .categorydiv #category-tabs>li,
        #tagsdiv-post_tag .tagcloud-link,
        #toplevel_page_edit-post_type-acf-field-group,
        #toplevel_page_limit-login-attempts,
        #toplevel_page_ivory-search,
        #toplevel_page_members
        { display: none; }
        #major-publishing-actions
        { background: transparent; }</style>';
    }
}
add_action('admin_head', 'hide_admin_bar_comments');

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

function remove_editor_notices() {
    remove_all_actions('admin_notices'); // 관리자 공지 제거
    remove_all_actions('all_admin_notices'); // 모든 관리자 공지 제거
}
add_action('admin_init', 'remove_editor_notices');


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

