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

            <div class="post-meta d-flex my-2">
                <p class="meta">분류 <span class="badge badge__dark">
                    <?php echo get_the_category_list(',');?></span></p>
                <p class="meta">단계 <span class="badge badge__blue">
                    <?php custom_get_the_tax_meta('project_state');?></span></p>
                <p class="meta">공종 <span class="badge badge__green">
                    <?php custom_get_the_tax_meta('system_type');?></span></p>
                <p class="meta">키워드 <span class="badge badge__yellow">
                    <?php custom_get_the_tag_meta();?></span></p>

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