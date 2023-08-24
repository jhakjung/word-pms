<?php get_header();

get_template_part('template-parts/sections/section', 'prjStateForSingle'); ?>

<hr>

<div class="main container">
	<div class="row pt-1 pb-4">

		<div class="col-md-3">
            <?php get_template_part('template-parts/sections/section', 'docAsideArchive'); ?>
        </div> <!-- col-3 -->

        <div class="col-md-9">
            <?php get_template_part('template-parts/sections/section', 'docMainArchive'); ?>
        </div> <!-- col-9 -->

	</div> <!-- row -->
</div> <!-- main -->

<?php get_footer(); ?>