<div class="post-title fs-3">
    <span><?php the_title(); ?></span>
    <div class="float-right d-inline-block">
        <span class="post-title__doc"><?php custom_get_the_author();?></span>
        <span class="post-title__doc"> | </span>
        <span class="post-title__doc"><?php custom_get_the_time(); ?></span>
    </div>
</div>

<hr>

<div class="post-content mt-2 px-1">
    <!-- 요약글 -->
    <?php if (has_excerpt()) : ?>
        <div class="excerpt border p-3 bg-warning bg-opacity-10">
            <?php echo get_the_excerpt(); ?>
        </div>
        <br>
    <?php endif; ?>

    <!-- 컨텐트 내용 -->
    <div class="content">
        <?php the_content(); ?>
    </div>

    <hr id="my_hr" class="mb-4 hr-1">

    <!-- 댓글 -->
    <div class="comment">
        <?php comments_template(); ?>
    </div>
</div> <!-- post-content -->
