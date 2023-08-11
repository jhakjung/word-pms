<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
  	<title><?php wp_title(' | ', 'echo', 'right'); ?><?php bloginfo('name'); ?></title>
  	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- Responsive navbar-->
    <nav class="site-header navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container d-flex flex-sm-wrap justify-content-between align-content-center">
            <div class="w-auto float-left">
                <a class="fs-3 navbar-brand text-left" href="<?php echo esc_url(site_url('/')); ?>"><?php bloginfo('name'); ?></a>
            </div>

            <div class="mx-auto w-auto">
                <p>
                    <?php echo do_shortcode('[ivory-search id="7" title="AJAX Search Form"]'); ?>
                </p>
            </div>


            <div class="w-auto">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="#">이슈관리</a></li>
                <li class="nav-item"><a class="nav-link" href="#">성과물</a></li>
                <li class="nav-item"><a class="nav-link" href="#">안전관리</a></li>
                <li class="nav-item"><a class="nav-link" href="#">공정관리</a></li>
                <li class="nav-item"><a class="nav-link" href="#!">자료실</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo esc_url(site_url('/test')); ?>">Test</a></li>
                </ul>
            </div>




        </div>
    </nav>