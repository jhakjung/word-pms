<?php get_header(); ?>

<main id="content" class="site-content">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <span class="categories">
                        카테고리:
                        <?php
                        $categories = get_the_category();
                        $category_names = array();
                        foreach ( $categories as $category ) {
                            // 자식 카테고리가 있으면 부모 카테고리도 함께 출력
                            if ( $category->category_parent > 0 ) {
                                $parent_category = get_category( $category->category_parent );
                                $category_names[] = $parent_category->name . '>' . $category->name;
                            } else {
                                $category_names[] = $category->name;
                            }
                        }
                        echo implode( ', ', $category_names );
                        ?>
                    </span> |
                    <span class="tags">
                        #태그: <?php the_tags( '', ', ' ); ?>
                    </span> |
                    <span class="author">
                        작성자: <?php the_author(); ?>
                    </span> |
                    <span class="posted-on">
                        작성일: <?php echo get_the_date( 'y/m/d' ); ?>
                    </span>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php the_content(); ?>
            </div><!-- .entry-content -->

        </article><!-- #post-## -->
    <?php
        endwhile;
    endif;
    ?>
</main><!-- #content -->

<?php get_footer(); ?>
