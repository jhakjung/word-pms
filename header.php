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
<nav class="siteHeader mb-2 py-3">
<div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-2">
    <!-- 사이트 이름 -->
    <div class="site-name fs-3 text-dark mb-3 mb-md-0 pr-3">
        <a href="<?php echo esc_url(site_url('/')); ?>"><?php bloginfo('name'); ?></a>
    </div>

    <!-- 검색창 -->
    <div class="custom-search flex-grow-1 mb-3 mb-md-0 mx-lg-5 mx-md-5 mx-sm-0 px-lg-5 px-md-5 px-sm-0">
        <?php echo do_shortcode('[ivory-search id="7" title="AJAX Search Form"]'); ?>
    </div>

    <!-- 버튼 영역 -->
    <div class="d-flex flex-wrap justify-content-end align-items-center gap-3 pl-3">


    <?php
// 포스트 삭제 처리
if (isset($_POST['delete_post']) && isset($_POST['post_id'])) {
    // 현재 사용자 ID와 게시물 작성자 ID 확인
    $current_user_id = get_current_user_id();
    $post_id = intval($_POST['post_id']);
    $post_author_id = get_post_field('post_author', $post_id);

    // 게시물 작성자와 현재 사용자가 일치하거나 관리자인 경우 삭제
    if (($current_user_id == $post_author_id) || current_user_can('administrator')) {
        wp_delete_post($post_id, true); // 'true'는 영구 삭제

        // return home_url();

        // 리다이렉트 전에 어떤 출력도 없어야 합니다.
        wp_redirect(home_url()); // 삭제 후 홈페이지로 리다이렉트
        exit; // 반드시 exit()을 호출하여 리다이렉트 후 스크립트 종료
    }
}
?>

<!-- Single 페이지 + 로그인 여부 확인 -->
<?php if (is_singular() && is_user_logged_in()) :
    global $post;
    $current_user_id = get_current_user_id(); // 현재 사용자 ID
    $post_author_id = $post->post_author; // 게시물 작성자 ID

    // 작성자 본인일 경우에만 수정 버튼과 삭제 버튼 표시
    if ($current_user_id == $post_author_id || current_user_can('administrator')) : ?>

        <!-- 수정 버튼 -->
        <a href="<?php echo get_edit_post_link($post->ID); ?>" class="text-primary">
            수정
        </a>

        <!-- 삭제 버튼 -->
        <form action="" method="post" style="display:inline;">
            <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
            <input type="submit" name="delete_post" value="삭제" class="text-danger border-0 bg-transparent" onclick="return confirm('정말 이 게시물을 삭제하시겠습니까?');" >
        </form>

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