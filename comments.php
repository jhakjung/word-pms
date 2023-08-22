<?php
if(post_password_required()) {
  	return;
}
?>
<div class="comments">
	<div class="row">
		<div class="w-100 mx-auto p-1">
			<h4 class="text-center text-secondary mb-4">
				<i class="fa fa-comments"></i>&nbsp;
				<?php
				$comments_number = get_comments_number();
				if(0 == $comments_number) {
				echo "0개의 코멘트";
				} else {
				printf(_nx( '1개의 코멘트', '%1$s개의 코멘트', $comments_number, 'comments title', 'bestmedical' ), number_format_i18n( $comments_number ));

				}
				?>
			</h4>
		<!-- <br> -->

		<!-- Comments List: inc폴더의 comment-template.php로 이동 -->
		<!-- Comments Pagination -->
		<?php
		wp_list_comments('type=comment&callback=bestmedical_comments_list');
		// wp_list_comments('type=pings&callback=bestmedical_comments_list'); // If ping & trackback is allowed
		bestmedical_comments_pagination();
		?>
		<!-- Comments Form -->
		<div class="media mt-3"> <!-- media-body 클래스부터는 template 파일 -->
			<div class="media-body">
				<?php comment_form(bestmedical_comments_template()); ?>
				<?php if(!comments_open() && get_comments_number()) {
					if(is_single()) { ?>
				<h4 class="no-comments text-center">
					코멘트가 없습니다.
				</h4>
				<?php }
				} ?>
			</div>
		</div>
	</div>
	<br>
	</div>
</div>