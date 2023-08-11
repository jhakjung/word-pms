<?php get_header();?>

<?php get_template_part('template-parts/sections/section', 'stage_cat_list'); ?>
<?php get_template_part('template-parts/sections/section', 'tax_title'); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <header class="page-header">
            <?php
            $taxonomy = get_queried_object();
            echo '<h1 class="page-title">' . esc_html($taxonomy->name) . '</h1>';
            ?>
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

<?php get_footer(); ?>