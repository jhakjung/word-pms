<?php

// 포스트 메타: 카테고리 출력 함수
function custom_get_postmet_parent_category($class = 'badge badge__blue text-white') {
    $categories = get_the_category();
    $category_names = array();
    $parent_categories = array();

    // 부모 카테고리만 한 번 추가
    foreach ( $categories as $category ) {
        if ( $category->category_parent == 0 ) {
            $parent_categories[] = $category;
        }
    }

    // 부모 카테고리 먼저 출력
    foreach ( $parent_categories as $parent_category ) {
        $category_names[] = '<a href="' . get_category_link( $parent_category->term_id ) . '"><span class="' . $class . '">' . $parent_category->name . '</span></a>';
    }

    // 자식 카테고리 출력 (부모 카테고리가 출력된 후 추가)
    foreach ( $categories as $category ) {
        if ( $category->category_parent != 0 ) {
            $category_names[] = '<a href="' . get_category_link( $category->term_id ) . '"><span class="' . $class . '">' . $category->name . '</span></a>';
        }
    }

    // 카테고리 리스트 출력
    echo implode(' ', $category_names);  // 태그 간격과 동일하게 공백으로 구분
}

// 포스트 메타: 카테고리 출력 함수
// 포스트 메타: 카테고리 출력 함수
function custom_get_postmeta_category($class = 'badge badge__blue text-white') {
    $categories = get_the_category();
    $category_names = array();

    foreach ( $categories as $category ) {
        if ( $category->category_parent != 0 ) { // 자식 카테고리만 처리
            $parent = get_category($category->category_parent);

            // 부모 카테고리가 "documents"인 경우 "성과물: " 추가
            if ( $parent && $parent->slug === 'documents' ) {
                $category_names[] = '<a href="' . get_category_link( $category->term_id ) . '"><span class="' . $class . '">성과물: ' . $category->name . '</span></a>';
            } else {
                $category_names[] = '<a href="' . get_category_link( $category->term_id ) . '"><span class="' . $class . '">' . $category->name . '</span></a>';
            }
        }
    }

    // 카테고리 리스트 출력
    echo implode(' ', $category_names);  // 태그 간격과 동일하게 공백으로 구분
}


// 포스트 메타: 포스트 슬러그 출력 함수
function custom_get_postmeta_postslug() {
	$slug = get_post_field('post_name', get_the_ID()); // 슬러그

	return $slug;
}

// 포스트 메타: author 출력
function custom_get_the_author() {
    $post_author_id = get_post_field('post_author', get_the_ID());
    $author_posts_url = get_author_posts_url($post_author_id);
    $display_name = get_the_author_meta('display_name', $post_author_id);
    echo '<span class="badge bg-light-dark"><a href="' . esc_url($author_posts_url) . '">' . esc_html($display_name) . '</a></span>';
    // echo $display_name;
}

// 포스트 메타: time 출력
function custom_get_the_time() {
	$current = current_time('U');
	$posted = get_the_time('U');
	$diff = $current - $posted;
	echo the_time('y-m-d');
}

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
