<!-- Row 2: Favorite Section -->
<div class="section-title"> <i class="fa fa-star" aria-hidden="true"></i> 즐겨찾기</div>
<div class="favoriteList d-flex justify-content-center mb-3 gap-2">
    <?php
// "즐겨찾기" 카테고리의 슬러그로 게시글을 조회
$favorite_category = 'favorite'; // "즐겨찾기" 카테고리 슬러그
$args = [
    'category_name' => $favorite_category, // 슬러그로 카테고리 필터링
    'posts_per_page' => -1, // 모든 게시글 출력
    'post_status' => 'publish', // 공개된 게시글만
];
$query = new WP_Query($args);

// 게시글이 있을 경우 제목 출력
if ($query->have_posts()):
    while ($query->have_posts()): $query->the_post();?>
    <!-- <a href="<?php the_permalink(); ?>" class="btn btn-warning"><?php the_title(); ?></a> -->
    <a href="<?php the_permalink(); ?>"><span class="badge badge__orange"><?php the_title(); ?></span></a>
    <?php endwhile;
    wp_reset_postdata(); // 쿼리 후 글로벌 $post 객체 초기화
else: ?>
    <p>즐겨찾기 카테고리에 게시글이 없습니다.</p>
<?php endif;?>
</div> <!-- end of favorites -->

<hr>