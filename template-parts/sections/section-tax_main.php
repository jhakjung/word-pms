<div class="col-lg-9 content-area">
    <main id="main" class="site-main">

        <header class="page-header">
            <?php
            $taxonomy = get_queried_object();
            $taxonomy_info = get_taxonomy($taxonomy->taxonomy);
            echo $taxonomy_info->labels->name; // taxonomy label
            echo $taxonomy->name; // taxonomy term

            // if ($taxonomy && $taxonomy->taxonomy === 'project_state') {
            //     $taxonomy_info = get_taxonomy($taxonomy->taxonomy);
            //     if ($taxonomy_info) {
            //         echo '<h1 class="page-title">' . esc_html($taxonomy_info->labels->name) . '</h1>';
            //     } else {
            //         echo 'Taxonomy info not found.';
            //     }
            // } else {
            //     echo 'Taxonomy not found or not "project_state".';
            // }

            // echo '<h1 class="page-title">' . esc_html($taxonomy->name) . '</h1>';
            // ?>
        </header><!-- .page-header -->

        <?php
        $terms = get_terms(array(
            'taxonomy' => 'project_state',
            'hide_empty' => false, // 빈 Term도 출력
        ));

        if (!empty($terms) && !is_wp_error($terms)) {
            echo '<ul>';
            foreach ($terms as $term) {
                echo '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo 'No terms found.';
        }
        ?>

    </main><!-- #main -->
</div><!-- #primary -->