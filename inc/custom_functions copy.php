<?php

function hide_admin_bar_comments1()
{
    if (!current_user_can('administrator')) {
        echo '<style>
        #wp-content-wrap .wp-editor-meta,
        #ed_toolbar,
        #ed_toolbar > input,
        .wp-editor-tabs,
        .mce-toolbar-grp,
        .categorydiv #category-tabs>li,
        #tagsdiv-post_tag .tagcloud-link,
        { display: none; }
        </style>';
    }
}
add_action('admin_head', 'hide_admin_bar_comments');

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



// ************************ //
// ***** 테이블 삽입 ******* //
// ************************ //

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
