<ul class="navbar-nav d-flex align-items-center">

                    <li class="nav-item <?php if (get_post_type() == 'post' AND is_category('my_category')) { echo 'active'; } ?>"><a class="nav-link" href="<?php echo custom_cat_archive_link('my_category'); ?>">MY</a></li>
                    <li class="nav-item" style="padding-left:10px">
                        <?php
                        if(is_user_logged_in() AND current_user_can('subscriber')) { ?>
                            <a href="<?php echo wp_logout_url();  ?>" class="pl-3 my_badge">
                            <span class="text-warning" style="font-size:0.95rem;font-weight:300">나가기</span>
                            </a>
                        <?php } ?>
                    </li>
                </ul>


                <ul class="navbar-nav d-flex align-items-center">
    <li class="nav-item <?php if (get_post_type() == 'post' AND is_category('my_category')) { echo 'active'; } ?>">
        <a class="nav-link" href="<?php echo custom_cat_archive_link('my_category'); ?>">MY</a>
        <ul class="dropdown-menu">
            <?php
            // 'my_category'의 하위 카테고리 목록을 가져오는 코드
            $child_categories = get_categories(array(
                'child_of' => get_term_by('slug', 'my_category', 'category')->term_id
            ));

            foreach ($child_categories as $child_category) {
                echo '<li><a class="dropdown-item" href="' . get_category_link($child_category->term_id) . '">' . $child_category->name . '</a></li>';
            }
            ?>
        </ul>
    </li>
    <li class="nav-item" style="padding-left:10px">
        <?php
        if (is_user_logged_in() AND current_user_can('subscriber')) { ?>
            <a href="<?php echo wp_logout_url();  ?>" class="pl-3 my_badge">
                <span class="text-warning" style="font-size:0.95rem;font-weight:300">나가기</span>
            </a>
        <?php } ?>
    </li>
</ul>
