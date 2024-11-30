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
        <div class="container d-flex flex-sm-wrap justify-content-between align-items-center py-2">
        <!-- 사이트 이름 -->
            <div class="site-name fs-2 text-primary">
                <a href="<?php echo esc_url(site_url('/')); ?>"><?php bloginfo('name'); ?></a>
            </div>

            <!-- 검색창 -->
            <div class="mx-auto custom-search w-50">
                <?php echo do_shortcode('[ivory-search id="7" title="AJAX Search Form"]'); ?>
            </div>

            <!-- 버튼 영역 -->
            <div class="editBtn">
                <div class="d-flex align-items-center">
                    <!-- Single 페이지 + 로그인 여부 확인 -->
                    <?php if (is_singular() && is_user_logged_in()) : ?>
                        <?php
                        global $post;
                        $current_user_id = get_current_user_id(); // 현재 사용자 ID
                        $post_author_id = $post->post_author; // 게시물 작성자 ID
                        ?>
                        <!-- 수정 버튼: 관리자 또는 작성자 본인만 표시 -->
                        <?php if (current_user_can('administrator') || $current_user_id === $post_author_id) : ?>
                            <a href="<?php echo get_edit_post_link($post->ID); ?>" class="btn btn-outline-primary ms-2">
                                수정
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- 작성 버튼 -->
                    <a href="<?php echo admin_url('post-new.php'); ?>" class="btn btn-outline-primary ms-2">
                        작성
                    </a>
                </div>
            </div>

        </div>
    </nav>