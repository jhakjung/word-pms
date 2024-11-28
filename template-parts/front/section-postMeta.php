<div class="post-meta d-flex flex-wrap my-2">
                <span class="meta"><i class="fas fa-sitemap"></i>
                카테고리:  <?php custom_get_postmeta_category(); ?></span>


                <span class="meta"><i class="fas fa-tag"></i><?php custom_get_the_tax_meta('post_tag', 'badge badge__dark');?></span>


                <span class="meta"><i class="fas fa-comments"></i>&nbsp;<span class="badge badge__comment"><?php custom_get_comments_number();?></span></span>

                <span class="meta"><i class="fas fa-user"></i><span class="badge__author">
                    <?php custom_get_the_author(); ?></span></span>
                <?php // if(is_single()) { ?>
                    <span class="meta"><i class="fas fa-clock"></i><span class="badge__author">
                    <?php custom_get_the_time(); ?></span></span>
                <!-- <?php // } ?> -->
            </div>