<!-- SECTION: Taxonomy Title
====================================================== -->
<section class="container">
    <div class="row">
        <div class="col-8">
            <h3 class="page-header">
                <?php
                $taxonomy = get_queried_object();
                echo esc_html($taxonomy->name);
                ?>
            </h3><!-- .page-header -->

        </div>
        <div class="col-4">
            <span>"전체"</span>
            <span>"해결"</span>
            <span>"미결"</span>
            <span>"종결"</span>
        </div>
    </div>
</section>