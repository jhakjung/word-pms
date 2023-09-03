<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();

                // 포스트 타입을 가져옵니다
                $post_type = get_post_type();
                $comments = get_comments(array(
                    'post_id' => $post->ID
                ));
                $target_word = get_search_query();
                $found_in_comments = false;

                foreach ($comments as $comment) {
                    if (strpos($comment->comment_content, $target_word) !== false) {
                    $found_in_comments = true;
                    break;
                } else {
                    // 검색 결과가 '첨부파일'인지 '댓글'인지 판별하여 출력합니다
                    if ($post_type == 'attachment') {
                        $post_type_name = "첨부파일";
                    } elseif ($post_type == 'post') {
                        $post_type_name = "글";
                        if ($found_in_comments) { echo "첨부";}
                    } elseif ($post_type == 'document') {
                        $post_type_name = "성과물";
                        if ($found_in_comments) { echo "첨부";}
                    }
                    else {
                        $post_type_name = "";
                        echo "검색 결과 없음";
                    }
                }
                echo $post_type_name;
            }
            endwhile;

            // 페이지네이션을 표시합니다
            the_posts_pagination(array(
                'prev_text' => __('이전 페이지', 'your-theme'),
                'next_text' => __('다음 페이지', 'your-theme'),
            ));
        else :
            // 검색 결과가 없는 경우 메시지를 표시합니다
            echo '<p>검색 결과가 없습니다.</p>';
        endif;
        ?>
    </main>
</div>

