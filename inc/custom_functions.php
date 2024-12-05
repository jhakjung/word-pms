<?php

function redirect_to_login_on_home() {
    // 첫 화면(홈페이지)인지 확인
    if (is_front_page() && !current_user_can('manage_options') && !is_user_logged_in()) {
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

//<!-------------즐겨찾기 관련 구현-------------
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
//---------------즐겨찾기 관련 구현----------!>

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

// <br> 삽입 방지
add_filter('the_content', function ($content) {
    if (strpos($content, '<table') !== false) {
        remove_filter('the_content', 'wpautop');
    }
    return $content;
}, 9);

// add_filter('tiny_mce_before_init', function ($settings) {
//     $settings['content_css'] = ''; // TinyMCE 기본 스타일 제거
//     return $settings;
// });

