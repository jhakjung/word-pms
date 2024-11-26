<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
  	<title><?php wp_title(' | ', 'echo', 'right'); ?><?php bloginfo('name'); ?></title>
      <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Song+Myung:400" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Black+Han+Sans:400" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Gothic+A1:100" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

  	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- Responsive navbar-->
    <nav class="site-header mb-2">
        <div class="container d-flex flex-sm-wrap justify-content-between align-content-center py-3">
            <div class="w-auto float-left">
                <a class="site-name fs-3 text-dark" href="<?php echo esc_url(site_url('/')); ?>"><?php bloginfo('name'); ?></a>
            </div>

            <div class="mx-auto w-auto custom-search w-50">
                    <?php echo do_shortcode('[ivory-search id="7" title="AJAX Search Form"]'); ?>
            </div>

            <!-- <div class="w-auto float-right">
                <ul class="navbar-nav d-flex align-items-center">
                    <li>
                        <span class="btn btn-success"><a class="fs-6" href="<?php echo admin_url('post-new.php'); ?>">작성</a></span>
                    </li>
                </ul>
            </div> -->
            <div class="w-auto float-right">
                <div class="d-flex align-items-center">

                    <!-- 수정 버튼 -->
                    <?php if (is_singular() && is_user_logged_in()) : // Single 페이지 + 로그인 여부 확인 ?>
                        <?php
                        global $post;
                        $current_user_id = get_current_user_id(); // 현재 사용자 ID
                        $post_author_id = $post->post_author;    // 게시물 작성자 ID

                        // 관리자이거나 작성자 본인일 경우에만 표시
                        if (current_user_can('administrator') || $current_user_id === $post_author_id) :
                        ?>
                                <span class="btn btn-dark ms-2">
                                    <a class="fs-6 text-white" href="<?php echo get_edit_post_link($post->ID); ?>">수정</a>
                                </span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- 작성 버튼 -->
                        <span class="btn btn-danger ms-2">
                            <a class="fs-6 text-white" href="<?php echo admin_url('post-new.php'); ?>">작성</a>
                        </span>

                </div>
</div>





        </div>
    </nav>