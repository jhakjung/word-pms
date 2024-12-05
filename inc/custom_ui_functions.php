<?php

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
    if (!current_user_can('administrator')) {
        echo '<style>
        #save-post, /* 임시글로 저장 버튼 숨기기 */
        #post-preview, /* 미리보기 버튼 숨기기 */
        .misc-pub-section, /* 상태, 가시성, 즉시발행 숨기기 */
        #minor-publishing, /* 공개 박스 하단 숨기기 */
        #contextual-help-link-wrap, /* 도움말 메뉴 */
        #screen-options-link-wrap, /* 화면 옵션 */
        #postimagediv, /* 특성이미지 설정 탭 숨기기 */
        #edit-slug-box, /* 고유주소 편집 라인 숨기기 */
        #category-add-toggle, /* 새 카테고리 추가 */
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

// 관리자 화면에서 글 메뉴만 보이게
function restrict_admin_menu_for_subscribers()
{
    if (is_admin()) {
        if (!current_user_can('administrator')) {
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
