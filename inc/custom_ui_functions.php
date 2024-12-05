<?php

// 어드민 환경에서 광고 팝업 제거
function remove_editor_notices() {
    remove_all_actions('admin_notices'); // 관리자 공지 제거
    remove_all_actions('all_admin_notices'); // 모든 관리자 공지 제거
}
add_action('admin_init', 'remove_editor_notices');

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
        #postexcerpt .inside p,
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

function customize_editor_for_subscribers()
{
    if (!current_user_can('administrator')) {
        echo '<style>
            /* 입력 박스 맨 아래 상태 정보 숨기기 */
            #wp-content-wrap .wp-editor-meta { display: none !important; }
        </style>';

        echo '<script type="text/javascript">
            jQuery(document).ready(function($) {
                /* "미디어 추가" 버튼의 텍스트를 "파일 추가"로 변경 */
                $("#insert-media-button").attr("title", "파일 추가").text("파일 추가");
                }
            });

        </script>';
    }
}
add_action('admin_head-post.php', 'customize_editor_for_subscribers');
add_action('admin_head-post-new.php', 'customize_editor_for_subscribers');
