<!-- Row 3: Issue Section -->
<div class="section-title"><i class="fa fa-info" aria-hidden="true"></i> 이슈</div>
<div class="tagsList d-flex flex-wrap gap-2 justify-content-center mb-3">
<?php
// 워드프레스 함수로 태그 목록 가져오기
$tags = get_tags([
    'hide_empty' => false, // 게시글에 사용되지 않은 태그도 포함
]);
// print_r($tags); // 태그 목록 확인

// 태그가 있을 경우 버튼 출력
if ($tags):
    foreach ($tags as $tag): ?>
    <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>"><span class="badge badge__blue text-white">#<?php echo $tag->name; ?></span></a>
    <?php endforeach;
else: ?>
    <p>태그가 없습니다.</p>
<?php endif;?>
</div> <!-- end of 이슈 -->

<hr>