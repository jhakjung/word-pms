<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
  	<title><?php wp_title(' | ', 'echo', 'right'); ?><?php bloginfo('name'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css?family=Song+Myung:400" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Black+Han+Sans:400" rel="stylesheet"> -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Gothic+A1:100" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Myeongjo&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

  	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- Responsive navbar-->
<nav class="bg-light border-bottom border-1 bg-gradient mb-1 py-2">
<div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-2">
    <!-- 사이트 이름 -->
    <div class="site-name fs-4 text-primary mb-3 mb-md-0 pr-3">
        <a href="<?php echo esc_url(site_url('/')); ?>"><?php bloginfo('name'); ?></a>
    </div>

    <!-- 검색창 -->
    <div class="custom-search flex-grow-1 mb-3 mb-md-0 mx-lg-5 mx-md-5 mx-sm-0 px-lg-5 px-md-5 px-sm-0">
        <?php echo do_shortcode('[ivory-search id="7" title="AJAX Search Form"]'); ?>
    </div>

    <!-- 버튼 영역 -->
    <div class="d-flex flex-wrap justify-content-end align-items-center gap-3 pl-3">
        <!-- Single 페이지 + 로그인 여부 확인 -->
        <?php if (is_singular() && is_user_logged_in()) :
        global $post;
            $current_user_id = get_current_user_id(); // 현재 사용자 ID
            $post_author_id = $post->post_author; // 게시물 작성자 ID

            // 작성자 본인일 경우에만 수정 버튼 표시
            if ($current_user_id == $post_author_id) : ?>
                <a href="<?php echo get_edit_post_link($post->ID); ?>" class="text-primary">
                    수정
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <!-- 작성 버튼 -->
        <a href="<?php echo admin_url('post-new.php'); ?>" class="text-primary">
            자료등록
        </a>

        <!-- 로그아웃 버튼 -->
        <a href="<?php echo wp_logout_url(home_url()); ?>" class="text-danger">
            로그아웃
        </a>
    </div>
</div>
</nav>