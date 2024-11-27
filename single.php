<?php get_header(); ?>

<div class="main container">
	<div class="row">
	<?php get_template_part('template-parts/front/section', 'aside'); ?>



<div class="col-lg-9">
    <div class="single-main container">
        <h3 class="post-title my-3"><?php the_title(); ?></h3>

            <?php // post-meta 부분 불러오기 ?>
            <div class="post-meta d-flex flex-wrap my-2">
            <span>카테고리<?php custom_get_postmeta_category(); ?>  | </span>

            <span class="meta"><i class="fas fa-tag"></i><?php custom_get_the_tax_meta('post_tag', 'badge badge__dark');?></span>


            <span class="meta"><i class="fas fa-comments"></i>&nbsp;<span class="badge badge__comment"><?php custom_get_comments_number();?></span></span>

            <span class="meta"><i class="fas fa-user"></i><span class="badge__author">
                <?php custom_get_the_author(); ?></span></span>
            <?php // if(is_single()) { ?>
                <span class="meta"><i class="fas fa-clock"></i><span class="badge__author">
                <?php custom_get_the_time(); ?></span></span>
            <!-- <?php // } ?> -->
        </div>



            <hr>

            <div class="post-content mt-2 px-1">
                <!-- 요약글 -->
                <?php if (has_excerpt()) : ?>
                    <div class="excerpt border py-2 px-3 mb-3 bg-warning bg-opacity-10">
                        <?php echo get_the_excerpt(); ?>
                    </div>
                <?php endif; ?>

                <!-- 컨텐트 내용 -->
                <div class="content">
                    <?php the_content(); ?>
                </div>

                <hr id="my_hr" class="my-4 hr-1">

                <!-- 댓글 -->
                <div class="">
                    <?php comments_template(); ?>
                </div>
            </div>
    </div>
</div>


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
                        <?php custom_get_postmeta_category(); ?>
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
