<?php

// 즐겨찾기 리스트 출력
function custom_get_favorites($class) {
    $favorite_taxonomy = 'favorite'; // 택소노미 이름
    $favorite_term_slug = '즐겨찾기'; // 즐겨찾기의 슬러그

    $args = [
        'post_type' => array('post', 'page'), // 포스트 유형
        'posts_per_page' => -1, // 모든 게시글 출력
        'post_status' => 'publish', // 공개된 게시글만
        'tax_query' => [
            [
                'taxonomy' => $favorite_taxonomy, // 택소노미 이름
                'field'    => 'slug', // 슬러그를 기준으로 검색
                'terms'    => $favorite_term_slug, // 슬러그 값
            ],
        ],
    ];

    $query = new WP_Query($args);

    // 게시글이 있을 경우 제목 출력
    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post(); ?>
            <a href="<?php the_permalink(); ?>"><span class="<?php echo $class; ?>"><?php the_title(); ?></span></a>
        <?php endwhile;
        wp_reset_postdata(); // 쿼리 후 글로벌 $post 객체 초기화
    else: ?>
        <p>즐겨찾기에 등록된 자료가 없습니다.</p>
    <?php endif;
}

// 포스트메타 태그 List 출력
function custom_get_tags($class) {
    $tags = get_the_tags();
    if ($tags) {
        $tag_links = array();
        foreach ($tags as $tag) {
            if ($tag) {
                $tag_name = $tag->name;
                $tag_link = get_tag_link($tag->term_id);
                $tag_links[] = '<span class="' . $class .'"><a href="' . $tag_link . '">' ."#". $tag_name . '</a></span>';
            }
        }
        echo implode(' ', $tag_links);
    } else {
        echo "-";
    }
}

// 전체 태그 List 출력
function custom_get_all_tags($class) {
    // 태그를 사용된 횟수(count) 기준으로 가져오기
    $tags = get_tags([
        'hide_empty' => true,  // 게시글에 사용된 태그만 포함 (사용되지 않은 태그는 제외)
    ]);

    // 디버깅: 태그 리스트 출력
    // echo '<pre>';
    // print_r($tags);
    // echo '</pre>';

    // 태그를 count 기준으로 내림차순으로 정렬
    usort($tags, function($a, $b) {
        return $b->count - $a->count; // 내림차순 정렬 (많이 사용된 태그가 앞에 오도록)
    });

    if ($tags) {
        $tag_links = array();
        foreach ($tags as $tag) {
            if ($tag) {
                $tag_name = $tag->name;
                $tag_link = get_tag_link($tag->term_id);
                $tag_links[] = '<span class="' . esc_attr($class) .'"><a href="' . esc_url($tag_link) . '">' . "#" . esc_html($tag_name) . '</a></span>';
            }
        }
        echo implode(' ', $tag_links);
    } else {
        echo "-";
    }
}



// 성과물 List 출력
function custom_get_document_category() {
    // "document" 카테고리의 자식 카테고리 가져오기
    $parent_category_id = get_cat_ID('성과물'); // "성과물" 카테고리의 ID
    $args = [
        'parent' => $parent_category_id, // 부모 카테고리 ID로 자식 카테고리 가져오기
        'hide_empty' => false, // 사용되지 않은 카테고리도 포함
        'orderby' => 'slug', // 슬러그명으로 정렬
        'order' => 'ASC', // 오름차순 정렬
    ];
    $child_categories = get_categories($args);

    // 자식 카테고리마다 .card를 출력
    foreach ($child_categories as $category) {
        $category_name = $category->name;
        $category_link = get_category_link($category->term_id);
        $category_links[] = '<span class="badge bg-darkgray bg-gradient m-1"><a href="' . $category_link . '">' . $category_name . '</a></span>';
    }
    echo implode(' ', $category_links);
}

// 택소노미별 리스트를 출력
function custom_get_tax_list($taxonomy, $class) {
    $hide = true;
    // if ($taxonomy == 'project_state') { $hide = false; }
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => $hide, // 빈 Term도 출력
        'orderby' => 'slug',
        'order' => 'ASC'
    ));
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $term_name = $term->name;
            $term_name = preg_replace('/^\d{2}_/', '', $term_name); // project_state 조작
            $term_link = get_term_link($term);

            // Get the count of 'document' type assigned to the term
            $args = array(
                'post_type' => 'post', // Replace with your custom post type slug
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $term->slug,
                    ),
                ),
            );
            $term_query = new WP_Query($args);
            $term_count = $term_query->found_posts;

            echo '<span class="' . $class . '"><a class="my_badge" href="' . $term_link . '">' . $term_name . ' (' . $term_count . ')</a></span>';
        }
    }
}

// 카테고리 archive 페이지로 연결해주는 링크 생성 함수
function custom_cat_archive_link($category_slug) {
    $category = get_category_by_slug($category_slug);
    if ($category) {
        $category_archive_url = get_term_link($category);
        if (!is_wp_error($category_archive_url)) {
            return $category_archive_url;
        }
    }
    return '';
}

// Archive title을 출력할 때 괄호를 없애주는 함수
function custom_get_the_archive_title() {
	$archive_title = get_the_archive_title();
	$archive_title = str_replace(array('[', ']'), '', $archive_title);

	return $archive_title;
}