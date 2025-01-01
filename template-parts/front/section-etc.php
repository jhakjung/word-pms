<!-- Row 4: 성과물 Section with Nested Table -->
<?php
// 출력할 카테고리의 슬러그 리스트
$target_category_slugs = ['cat01', 'cat02', 'cat03'];

// 카테고리 정보를 가져옵니다.
$category_data = [];

foreach ($target_category_slugs as $slug):
    // 카테고리 정보 가져오기
    $category = get_category_by_slug($slug);

    if ($category) {
        // 해당 카테고리의 게시글 리스트 가져오기
        $category_posts = new WP_Query([
            'cat' => $category->term_id, // 해당 카테고리의 게시글
            'posts_per_page' => -1, // 모든 게시글 출력
            'post_status' => 'publish', // 공개된 게시글만
            'orderby' => 'date', // 작성일 기준으로 정렬
            'order' => 'ASC', // 오름차순 정렬
        ]);

        // 카테고리 정보와 게시글을 배열에 저장
        $category_data[] = [
            'category' => $category,
            'category_posts' => $category_posts,
        ];
    }
endforeach;
?>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2 mb-4">
<!-- <div class="row g-2 mb-4"> -->
    <?php
    // 카테고리 정보가 있으면 출력
    foreach ($category_data as $data):
        $category = $data['category'];
        $category_posts = $data['category_posts'];

        // 카테고리 제목에 추가할 클래스 결정
        if ($category->slug == 'cat01') {
            $category_class = 'bg-danger text-white bg-gradient';
        } elseif ($category->slug == 'cat02') {
            $category_class = 'bg-vivid-purple text-white bg-gradient';
        } elseif ($category->slug == 'cat03') {
            $category_class = 'bg-success text-white bg-gradient';
        } else {
            $category_class = ''; // 기본 클래스 (없으면 비워둠)
        }
    ?>
        <div class="col">
            <div class="card">
                <a class="docs card-header text-center <?php echo esc_attr($category_class); ?>" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                    <?php echo esc_html($category->name); ?>
                </a>
                <ul class="list-group list-group-flush">
                    <?php if ($category_posts->have_posts()): ?>
                        <?php while ($category_posts->have_posts()): $category_posts->the_post(); ?>
                            <li class="list-group-item my-list-group-item1">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); // 쿼리 후 데이터 초기화 ?>
                    <?php else: ?>
                        <li class="list-group-item my-list-group-item1 text-gray-500">자료 없음</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
</div> <!-- end of 성과물 -->
