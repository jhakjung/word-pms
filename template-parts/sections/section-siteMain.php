    <div class="site-main container">

        <?php
        $post_count = 0;
        while(have_posts()) {
            the_post();
            $post_count++;
            $slug = get_post_field('post_name', get_the_ID()); // 슬러그
            ?>
            <div class="article mb-4">
                <div class="post-title fs-4">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <span class="post-title__slug"><?php echo '#' .$slug; ?></span>
                </div>

                <?php get_template_part('template-parts/sections/section', 'postMeta'); ?>

                <div class="post-content">
                    <p class="card-text">
                        <?php
                        if (has_excerpt()) {
                            echo get_the_excerpt(); ?>
                        <?php } else {
                            echo wp_trim_words(get_the_content(), 45);
                        } ?>
                    </p>
                </div>
            </div>

            <?php if ($post_count < $wp_query->post_count) { ?> <hr> <?php }

        } ?>
        <br>
    </div>
</div>
