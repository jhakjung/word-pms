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

            <div class="post-meta d-flex flex-wrap my-2">
                <span class="meta">분류
                    <?php custom_get_the_tax_meta('category', 'badge badge__dark');?></span>
                <span class="meta">단계
                    <?php custom_get_the_tax_meta('project_state', 'badge badge__blue');?></span>
                <span class="meta">공종
                    <?php custom_get_the_tax_meta('system_type', 'badge badge__green');?></span>

                <span class="meta">키워드<?php custom_get_the_tag_meta();?></span>





                <?php
                // 이슈상태 출력
                $terms = get_the_terms(get_the_ID(), 'issue_state');
                if ($terms) {
                    $term_name = $terms[0]->name;
                    if ($term_name == "미결") {
                        $class = "badge badge__red";
                    } elseif ($term_name == "해결") {
                        $class = "badge badge__teal";
                    } elseif ($term_name == "종결") {
                        $class = "badge badge__dark";
                    } else {
                        $class = "badge badge__purple";
                    }
                } ?>
                <p class="meta">이슈 <span class="<?php echo $class; ?>">
                    <?php custom_get_the_issue_meta(); ?></span></p>
                <p class="meta">작성 <span class="badge badge__light">
                    <?php custom_get_the_author(); ?></span></p>
                <p class="meta">일시 <span class="my_badge badge badge__light">
                    <?php custom_get_the_time(); ?></span></p>
            </div>

            <div class="post-content">
                <?php the_content(); ?>
            </div>

            <br>

            <?php if ($post_count < $wp_query->post_count) { ?> <hr> <?php }

        } ?>

        <br>

    </div>
</div>