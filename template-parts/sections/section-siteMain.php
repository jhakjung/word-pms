    <div class="site-main">

        <?php
        $post_count = 0;
        while(have_posts()) {
            the_post();
            $post_count++; ?>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_content(); ?>
            <?php if ($post_count < $wp_query->post_count) { ?> <hr> <?php } ?>
        <?php } ?>

    </div>
</div>