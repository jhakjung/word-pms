    <div class="site-main container">

        <?php
        $post_count = 0;
        while(have_posts()) {
            the_post();
            $post_count++;
            $slug = get_post_field('post_name', get_the_ID()); // 슬러그
            ?>

            <div class="post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <span class="post-title__slug"><?php echo '#' .$slug; ?></span>
            </div>
            <div class="post-meta d-flex mx-2">
                <span><?php echo "단계: ";?></span>
                <span><?php echo "공종: ";?></span>
                <span><?php echo "태그: ";?></span>
                <span><?php echo "이슈상태: ";?></span>
                <span><?php echo "작성일: ";?></span>
            </div>
            <!-- <p><?php echo get_field('project_state');?></p> -->


            <?php the_content(); ?>
            <?php if ($post_count < $wp_query->post_count) { ?> <hr> <?php } ?>
        <?php } ?>

    </div>
</div>