<div class="col-lg-9">
    <div class="single-main container">

        <?php
            $slug = get_post_field('post_name', get_the_ID()); // 슬러그
            ?>

            <div class="post-title fs-3 mt-3">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <span class="post-title__slug"><?php echo '#' .$slug; ?></span>
            </div>

            <?php // post-meta 부분 불러오기
            get_template_part('template-parts/sections/section', 'postMeta'); ?>

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
                    <?php echo the_content(); ?>
                </div>

                <hr id="my_hr" class="mb-4 hr-1">

                <!-- 댓글 -->
                <div class="">
                    <?php comments_template(); ?>
                </div>
            </div>
    </div>
</div>
