    <div class="site-main container">

        <?php
        $post_count = 0;
        while(have_posts()) {
            the_post();
            $post_count++;
            $slug = get_post_field('post_name', get_the_ID()); // 슬러그
            ?>
            <div class="article mb-3">
                <div class="pb-2">
                    <a class="post-title__archive" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <span class="post-title__slug text-muted"><?php echo "ID: ".custom_get_postmeta_postslug(); ?></span>
                </div>

            <div class="post-meta__archive">
                <span>📁카테고리&nbsp;<?php custom_get_postmeta_category('badge badge__blue bg-gradient text-white'); ?>&nbsp;&nbsp;</span>
                <span>🏷️태그&nbsp;<?php custom_get_tags("badge badge__yellow bg-gradient text-dark"); ?>&nbsp;&nbsp;</span>
                <span>🧑&nbsp;<?php custom_get_the_author(); ?></span>
                <span class="text-muted"><?php custom_get_the_time(); ?></span>
            </div>
                <div class="post-content">
                    <p class="card-text py-1">
                        <?php
                        if (has_excerpt()) {
                            echo get_the_excerpt(); ?>
                        <?php } else {
                            echo '';
                            //wp_trim_words(get_the_content(), 45);
                        } ?>
                    </p>
                </div>
            </div>

            <?php if ($post_count < $wp_query->post_count) { ?> <hr> <?php }

        } ?>
        <br>
    </div>
</div>
