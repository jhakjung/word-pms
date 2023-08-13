<!-- 버튼 형태의 프로젝트 단계 UI -->
<section class="d-flex flex-wrap justify-content-center border-bottom">
	<?php
	// 'project_state' Taxonomy에 속하는 Term들을 가져옴
	$terms = get_terms(array(
		'taxonomy' => 'project_state',
		'hide_empty' => false, // 빈 Term도 출력
	));

	$button_class = 'btn-light';
	echo '<a href="#" class="btn m-2 border ' . $button_class . '">전체단계</a>';

	foreach ($terms as $term) {
		if ($term) {
			$term_name = $term->name;
			$term_link = get_term_link($term);

			echo '<a href="' . $term_link . '" class="btn m-2 border ' . $button_class . '">' . $term_name . '</a>';
		}
	}
	?>
</section>