<!-- Row 2: Favorite Section -->
<div class="section-title"> <i class="fa fa-star" aria-hidden="true"></i> 즐겨찾기</div>
<div class="favoriteList d-flex justify-content-center mb-3 gap-2">

<?php
$favorite_taxonomy = 'favorite'; // 택소노미 이름
$favorite_term_slug = '즐겨찾기'; // 즐겨찾기의 슬러그

$args = [
    'post_type' => 'post', // 포스트 유형
    'posts_per_page' => -1, // 모든 게시글 출력
    'post_status' => 'publish', // 공개된 게시글만
    'tax_query' => [
        [
            'taxonomy' => $favorite_taxonomy, // 택소노미 이름
            'field'    => 'slug', // 슬러그를 기준으로 검색
            'terms'    => $favorite_term_slug, // 슬러그 값
        ],
    ],
];

$query = new WP_Query($args);

// 게시글이 있을 경우 제목 출력
if ($query->have_posts()):
    while ($query->have_posts()): $query->the_post(); ?>
        <a href="<?php the_permalink(); ?>"><span class="badge badge__orange"><?php the_title(); ?></span></a>
    <?php endwhile;
    wp_reset_postdata(); // 쿼리 후 글로벌 $post 객체 초기화
else: ?>
    <p>즐겨찾기에 등록된 자료가 없습니다.</p>
<?php endif; ?>

</div> <!-- end of favorites -->

<hr>