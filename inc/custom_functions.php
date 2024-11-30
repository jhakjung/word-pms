<?php

function redirect_to_login_on_home() {
    // 첫 화면(홈페이지)인지 확인
    if (is_front_page() && !current_user_can('manage_options') && !is_user_logged_in()) {
        wp_redirect(wp_login_url(home_url()));

        exit;
    }

}
add_action('template_redirect', 'redirect_to_login_on_home');


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

