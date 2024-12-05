<?php $slug = get_post_field('post_name', get_the_ID()); // 슬러그 ?>

<div class="post-title fs-3">
    <span><?php the_title(); ?></span>
    <div class="float-right d-inline-block">
        <i class="post-title__doc fas fa-user"></i>
        <span class="post-title__doc"><?php custom_get_the_author();?></span>
        <i class="post-title__doc fas fa-clock"></i>
        <span class="post-title__doc"><?php custom_get_the_time(); ?></span>
        <span class="post-title__doc"> | </span>
        <span class="post-title__doc"><?php echo '#' .$slug; ?></span>
    </div>
</div>

<hr>

<div class="post-content mt-2 px-1">
    <!-- 요약글 -->
    <?php if (has_excerpt()) : ?>
        <div class="excerpt border p-3 mb-3 bg-warning bg-opacity-10">
            <?php echo get_the_excerpt(); ?>
        </div>
    <?php endif; ?>

    <!-- 컨텐트 내용 -->
    <div class="content">
        <?php the_content(); ?>
    </div>

    <hr id="my_hr" class="my-4 hr-1">

    <!-- 댓글 -->
    <div class="comment">
        <?php comments_template(); ?>
    </div>
</div> <!-- post-content -->
