<div class="post-meta d-flex flex-wrap my-2">
                <span class="meta"><i class="fas fa-sitemap"></i>
                    <?php custom_get_the_tax_meta('category', 'badge badge__yellow');?></span>
                <span class="meta"><i class="fas fa-folder"></i>
                    <?php custom_get_the_tax_meta('project_state', 'badge badge__blue');?></span>
                <span class="meta"><i class="fas fa-cube"></i>
                    <?php custom_get_the_tax_meta('system_type', 'badge badge__green');?></span>
                <span class="meta"><i class="fas fa-tag"></i><?php custom_get_the_tax_meta('post_tag', 'badge badge__dark');?></span>

                <?php
                // 이슈상태 출력
                $terms = get_the_terms(get_the_ID(), 'issue_state');
                if ($terms) {
                    $term_name = $terms[0]->name;
                    if ($term_name == "미결") {
                        $class = "badge badge__darkOrange";
                    } elseif ($term_name == "해결") {
                        $class = "badge badge__green";
                    } elseif ($term_name == "종결") {
                        $class = "badge badge__purple";
                    } else {
                        $term_name = "";
                        $class = "";
                    }
                } ?>
                <span class="meta"><i class="fas fa-info"></i>&nbsp;<span class="<?php echo $class; ?>">
                    <?php custom_get_the_issue_meta(); ?></span></span>

                <span class="meta"><i class="fas fa-comments"></i>&nbsp;<span class="badge badge__comment"><?php custom_get_comments_number();?></span></span>

                <span class="meta"><i class="fas fa-user"></i><span class="badge__author">
                    <?php custom_get_the_author(); ?></span></span>
                <?php // if(is_single()) { ?>
                    <span class="meta"><i class="fas fa-clock"></i><span class="badge__author">
                    <?php custom_get_the_time(); ?></span></span>
                <!-- <?php // } ?> -->
            </div>