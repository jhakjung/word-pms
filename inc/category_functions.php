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

// 글 수정 시 자식 카테고리와 부모 카테고리 관계를 설정
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
// function add_parent_category_on_save($post_id) {
//     // 확인: 자동 저장이나 수정일 경우 동작하지 않음
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     // 확인: 현재 사용자가 글 편집 권한이 있는지 확인
//     if (!current_user_can('edit_post', $post_id)) {
//         return;
//     }

//     // 확인: 게시물 유형이 'post'인지 확인
//     if (get_post_type($post_id) !== 'post') {
//         return;
//     }

//     // 현재 글에 지정된 카테고리 가져오기
//     $categories = wp_get_post_categories($post_id);

//     // 부모 카테고리를 저장할 배열
//     $parent_categories = [];

//     foreach ($categories as $category_id) {
//         $parent_id = get_category($category_id)->parent;

//         // 부모 카테고리가 있을 경우 배열에 추가
//         if ($parent_id && !in_array($parent_id, $categories)) {
//             $parent_categories[] = $parent_id;
//         }
//     }

//     // 부모 카테고리를 기존 카테고리와 병합하여 저장
//     if (!empty($parent_categories)) {
//         wp_set_post_categories($post_id, array_merge($categories, $parent_categories));
//     }
// }
// add_action('save_post', 'add_parent_category_on_save');

// 자식 카테고리 저장 시 부모 카테고리 자동저장
// function add_parent_category_on_save($post_id) {
//     // 확인: 자동 저장이나 수정일 경우 동작하지 않음
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     // 확인: 현재 사용자가 글 편집 권한이 있는지 확인
//     if (!current_user_can('edit_post', $post_id)) {
//         return;
//     }

//     // 확인: 게시물 유형이 'post'인지 확인
//     if (get_post_type($post_id) !== 'post') {
//         return;
//     }

//     // 현재 글에 지정된 카테고리 가져오기
//     $categories = wp_get_post_categories($post_id);

//     // 부모 카테고리를 저장할 배열
//     $parent_categories = [];

//     foreach ($categories as $category_id) {
//         $parent_id = get_category($category_id)->parent;

//         // 부모 카테고리가 있을 경우 배열에 추가
//         if ($parent_id && !in_array($parent_id, $categories)) {
//             $parent_categories[] = $parent_id;
//         }
//     }

//     // 부모 카테고리를 기존 카테고리와 병합하여 저장
//     if (!empty($parent_categories)) {
//         $all_categories = array_merge($categories, $parent_categories);
//         wp_set_post_categories($post_id, $all_categories);

//         // 선택된 카테고리 갱신을 강제로 트리거
//         wp_cache_delete($post_id, 'post_meta');
//     }
// }
// add_action('save_post', 'add_parent_category_on_save');

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
