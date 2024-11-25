<aside class="col-lg-3 aside mr-3">
    <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-star"></i>&nbsp;&nbsp;즐겨찾기
        </div>
        <div class="card-body container">
            <ul class="favorite">
                <?php
                $args = array(
                    'category_name' => 'favorite',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                );

                $favorite_query = new WP_Query($args);

                if ($favorite_query->have_posts()) {
                    while ($favorite_query->have_posts()) {
                        $favorite_query->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php }
                    wp_reset_postdata();
                } else {
                    echo '';
                }
                ?>
            </ul>
        </div>
    </div>

    <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
        <i class="text-dark text-opacity-50 fas fa-folder"></i>&nbsp;&nbsp;프로젝트단계
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('project_state', 'fs-7 badge badge__blue m-1'); ?>
            </div>
        </div>
    </div>

    <!-- <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-cube"></i>&nbsp;&nbsp;공종
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('system_type', 'fs-7 badge badge__green m-1'); ?>
            </div>
        </div>
    </div> -->

    <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-tag"></i>&nbsp;&nbsp;키워드
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('post_tag', 'fs-7 badge badge__dark m-1'); ?>
            </div>
        </div>
    </div>


</aside>