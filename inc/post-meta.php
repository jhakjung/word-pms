<?php
// 카테고리 리스트 표시
function pms_category() {
	if (get_post_type() === 'document') {
		// 'document' 포스트 타입인 경우 'document_category' 사용
		$categories = get_the_terms(get_the_ID(), 'document_category');
		if ($categories && !is_wp_error($categories)) {
			$category_names = array();
			foreach ($categories as $category) {
				$category_names[] = $category->name;
			}
			echo '<span class="badge bg-vivid-cyan-blue ml-1 mr-2">' . implode(', ', $category_names) . '</span>';
		}
	} else {
		// 'post' 포스트 타입인 경우 기존 처리 방식 적용
		echo '<i class="text-dark text-opacity-25 fas fa-folder-open"></i>';
		echo '<span class="badge bg-vivid-cyan-blue ml-1 mr-2">' . get_the_category_list(', ') . '</span>';
	}
}

// 이슈 상태 표시
function pms_issue_state() {
	$issueStatus = get_field('issue_status');
	if ($issueStatus) {
		if ($issueStatus == '해결') {
			$strClass = "badge bg-vivid-cyan2 ml-1 mr-2";
			$issueStatusLink = site_url('/issue-solved');
		} elseif ($issueStatus == '미해결') {
			$strClass = "badge bg-vivid-red ml-1 mr-2";
			$issueStatusLink = site_url('/issue-unsolved');
		} elseif ($issueStatus == '종결') {
			$strClass = "badge badge-dark ml-1 mr-2";
			$issueStatusLink = site_url('/issue-closed');
		} else {
			return;
		}
		echo '<i class="text-dark text-opacity-25 fas fa-info"></i>';
		echo '<span class="' . $strClass . '"><a href="' . $issueStatusLink . '">' . $issueStatus . '</a></span>';
	}
}

// 키워드 표시
function pms_tag() {
	$tags = get_the_tags();
		if ($tags) { // 키워드가 존재하는 경우에만 실행
			echo '<i class="text-dark text-opacity-25 fas fa-tag"></i>';
			echo '<span class="badge bg-light-dark ml-1 mr-2">' . get_the_tag_list('', ', ', '') . '</span>';
		}
}

// 댓글 수 표시
function pms_comment() {
	$comment_count = get_comments_number();
	if ($comment_count > 0) {
		echo '<i class="text-dark text-opacity-25 fas fa-comments"></i>';
		echo '<span class="badge bg-vivid-amber ml-1 mr-2"><a href="' . get_comments_link() . '">' . $comment_count . '</a></span>';
	} elseif (comments_open()) {
		echo '<i class="text-dark text-opacity-25 fas fa-comments"></i>';
		echo '<span class="badge bg-vivid-amber ml-1 mr-2"><a href="' . get_comments_link() . '">0</a></span>';
	}
}

// 담당자 or 작성자 표시
function pms_in_charge() {
	echo '<i class="text-dark text-opacity-25 fas fa-user"></i>';
	// echo '<span class="text-muted">담당:&nbsp;</span>';

	$inCharge = get_field('in_charge');

	if ($inCharge) {
		$author_id = $inCharge['ID'];
		$author_posts_url = get_author_posts_url($author_id);
		$display_name = get_the_author_meta('display_name', $author_id);
		echo '<span class="text-muted author ml-1 mr-2"><a href="' . esc_url($author_posts_url) . '">' . esc_html($display_name) . '</a></span>';
	} else {
		pms_postedby();
	}
}

// 게시자 표시
function pms_postedby() {
	$post_author_id = get_post_field('post_author', get_the_ID());
	$author_posts_url = get_author_posts_url($post_author_id);
	$display_name = get_the_author_meta('display_name', $post_author_id);
	echo '<span class="text-muted author ml-1 mr-2"><a href="' . esc_url($author_posts_url) . '">' . esc_html($display_name) . '</a></span>';
}

// 게시일자 표시
function pms_posted_time() {
	echo '<i class="text-dark text-opacity-25 fas fa-clock"></i>'; ?>
		<small class="text-muted"><?php post_time(); ?></small>
<?php }

// Post Time
function post_time() {
	$current = current_time('U');
	$posted = get_the_time('U');
	$diff = $current - $posted;

	if($diff > 0 && $diff < 60*60*24*3) {
		echo sprintf(__('%s 전 게시됨', 'bestmedical'), human_time_diff($posted, $current));
	} else {
		echo the_time('y-m-d');
	}
}

// General Post Meta
function general_post_meta() {
	pms_category();
	pms_issue_state();
	pms_tag();
	pms_comment();
	pms_in_charge();
	pms_posted_time();
}

// 성과물 진행상태 표시
function progress_state() {
	$document_progress = get_field('progress_state', get_the_ID(), false);
		if (empty($document_progress)) {
			$document_progress = '';
		} elseif ($document_progress == '완료') {
			echo '<span class="float-right"><i class="vivid-cyan2 fas fa-check fa-sm"></i></span>';
		} elseif ($document_progress == '작성중') {
			echo '<span class="float-right"><i class="vivid-red fas fa-hourglass-half fa-sm"></i></span>';
		} else { // '해당없음'
			echo '<span class="float-right"><i class="light-dark fas fa-ban fa-sm"></i></span>';
		};
}