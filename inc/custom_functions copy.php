<?php

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

// 관리자가 아닌 경우 첫 접속은 홈으로 리다이렉션
add_action('admin_init', function () {
    if (is_user_logged_in()) {
        if (!current_user_can('administrator')) {
            // 'post-new.php'에 대한 예외 처리
            $current_page = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

            if (strpos($current_page, 'post-new.php') === false) {
                // 홈 화면으로 리디렉션
                wp_safe_redirect(home_url());
                exit;
            }
        }
    }
});

// 관리자가 아닌 경우 어드민바 숨기기
function hide_admin_bar_for_subscribers()
{
    if (!current_user_can('administrator')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_subscribers');

function add_post_capabilities_to_subscriber() {
    $role = get_role('subscriber');
    if ($role) {
        $role->add_cap('edit_posts');     // 구독자가 글 작성 가능
        $role->add_cap('edit_published_posts'); // 작성한 글 수정 가능
        $role->add_cap('publish_posts'); // 글 발행 가능
    }
}
add_action('init', 'add_post_capabilities_to_subscriber');

// function auto_publish_posts_for_subscriber($data, $postarr) {
//     if (current_user_can('subscriber')) {
//         $data['post_status'] = 'publish'; // 구독자가 작성한 글을 자동으로 발행 상태로 변경
//     }
//     return $data;
// }
// add_filter('wp_insert_post_data', 'auto_publish_posts_for_subscriber', 10, 2);

function include_subscriber_posts_in_archive($query) {
    // 관리자 페이지가 아니고, 메인 쿼리인지 확인
    if (!is_admin() && $query->is_main_query() && $query->is_archive()) {
        $query->set('post_status', array('publish', 'private')); // 발행 및 비공개 글 표시
    }
}
add_action('pre_get_posts', 'include_subscriber_posts_in_archive');
