<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h2 class="entry-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
    </header>

    <div class="entry-content">
        <?php the_excerpt(); ?>
    </div>

    <footer class="entry-footer">
        <?php
        // 포스트 타입을 가져옵니다
        $post_type = get_post_type();

        // 포스트 타입에 따라 다른 내용을 출력합니다
        if ($post_type == 'post') {
            // '포스트' 타입의 경우, 카테고리 목록을 표시합니다
            echo '<div class="entry-categories">';
            the_category(', ');
            echo '</div>';
        } elseif ($post_type == 'page') {
            // '페이지' 타입의 경우, 아무 내용도 출력하지 않습니다
        } else {
            // 기타 포스트 타입의 경우, 타입명을 표시합니다
            echo '<div class="entry-post-type">';
            echo '포스트 타입: ' . $post_type;
            echo '</div>';
        }
        ?>
    </footer>
</article>
