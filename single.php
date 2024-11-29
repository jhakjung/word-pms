<?php get_header(); ?>

<div class="main container">
	<div class="row">
	<?php get_template_part('template-parts/front/section', 'aside'); ?>



<div class="col-lg-9">
    <div class="single-main container">
        <h3 class="post-title my-3"><?php the_title(); ?><span class="text-muted fs-6 float-right"><?php echo "ID: ".custom_get_postmeta_postslug(); ?></span></h3>
            <?php // post-meta 부분 불러오기 ?>
        <div class="post-meta">
            <span>📁카테고리&nbsp;<?php custom_get_postmeta_category('badge badge__blue bg-gradient text-white'); ?>&nbsp;&nbsp;</span>
            <span>🏷️태그&nbsp;<?php custom_get_tags("badge badge__yellow bg-gradient text-dark"); ?>&nbsp;&nbsp;</span>
            <span>🧑&nbsp;<?php custom_get_the_author(); ?>&nbsp;</span>
            <span class="float-left"><?php custom_get_the_time(); ?> 작성</span>
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

<?php get_footer(); ?>
